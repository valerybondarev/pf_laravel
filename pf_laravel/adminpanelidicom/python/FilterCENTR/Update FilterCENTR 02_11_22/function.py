import os
import cv2
import zipfile
import numpy as np
import matplotlib.pyplot as plt


# убираем артефакты
def clear_artef(img):
    list_area_rect, list_img = [], []
    img[img<128], img[img>=128] = 0, 255
    img[-1:] = 0
    thresh = cv2.adaptiveThreshold(img, 255, cv2.ADAPTIVE_THRESH_GAUSSIAN_C, cv2.THRESH_BINARY_INV,11,7)
    # получаем список найденных  контуров
    contours, _ = cv2.findContours(thresh, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

    for cnt in contours:
        # высота, ширина рамки контура
        _, _, w, h = cv2.boundingRect(cnt)
        #  наполняем список площадей рамок контуров
        list_area_rect.append(w*h)
        Z = np.zeros((img.shape), np.uint8)
        #  наполняем список изображений с найдеными контурами
        list_img.append(cv2.drawContours(image=Z, contours=[cnt], contourIdx=-1, color=255, thickness=-1))
    # находим индекс с максимальной площадью и по нему возвращаем очищенное изображение
    return list_img[np.argmax(list_area_rect)]


#  читаем изображение с диска. закрываем верх изображения черной плашкой 
def read_img(dir: str, mirr) -> np.array:
    img = cv2.imread(dir, 0)[800:2848, 136:2184]
    img[:960] = 0
    img = clear_artef(img).astype(np.bool_).astype(np.int8)#( - 127.5) / 127.5
    img[img == 0] = -1
    # если левая нога, зеркалим
    if mirr == 'mirr2':
       img = cv2.flip(img, 1)
       # возвращаем изображение
    return np.expand_dims(img, axis=2) #shape np.array (2048, 2048, 1)


#  собираем одно изображение-массив (8-ми канальное (1, 2048, 2048, 8)) из 8-ми изображений (2048, 2048)
def get_img_for_predict(names, mirr, path):
    # читаем все 8 изображений в список
    list_img = list(map(lambda x: read_img(os.path.join(path, x), mirr), names))
    # конкатенируем в 8-ми канальный массив
    X1 = np.concatenate(list_img, axis=-1)
    return np.expand_dims(X1, axis=0) #shape np.array (1, 2048, 2048, 8)


def predict_img(model, img):
    #  получаем сгенерированное изображение
    img = model.predict(img)[0]*127.5 + 127.5
    # приводим значения пиксклей в диапазон: 0 - 255
    return np.where(img < 127.5, 0, 255)  #shape np.array (2048, 2048, 8)


def save_gen_img(list_img, dir_result, names, mirr, output_archive_file_path):
    with zipfile.ZipFile(output_archive_file_path, 'w') as z:
        fig = plt.figure(figsize=(24, 4), facecolor=(0.7,0.7,0.7))
        for i in range(8):
            plt.subplot(1,8,i+1)
            plt.axis('off')
            plt.title(f'{names[i]}_')
            # убираем последнюю ось
            im = list_img[i].reshape(2048,2048)
            # если левая нога зеркалим
            if mirr == 'mirr2':
                im = cv2.flip(im, 1)
            Z = np.zeros((3088, 2320))
            # приводим к оригинальному размеру
            Z[800:2848, 136:2184] = im
            # задаем имя файла с изображением
            #output_image_file_path = os.path.join(dir_result, f'gen_{names[i].split(".")[0]}.png')
            output_image_file_path = os.path.join(dir_result, f'{names[i].split(".")[0]}.png')
            # сохраняем, обязательно в '.png' формате
            cv2.imwrite(output_image_file_path, Z)
            # добавляем файл в ZIP-архив
            z.write(output_image_file_path, os.path.basename(output_image_file_path), compress_type=zipfile.ZIP_DEFLATED)
            # удаляем файл с изображением
            if os.path.exists(output_image_file_path):
                os.remove(output_image_file_path)
            # визуализируем
            #plt.imshow(im, cmap = 'gray')
        #plt.show()
