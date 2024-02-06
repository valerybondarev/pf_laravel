import os
import time
import shutil
import werkzeug
import numpy as np
from keras.models import load_model
import tensorflow as tf
import time, os, werkzeug, zipfile, cv2, shutil
from flask import Flask, render_template, make_response, request, Blueprint, send_file
from flask_restx import Api, Resource, fields, reqparse
from werkzeug.utils import secure_filename
from function import *


# настраиваем графическую карту
gpus = tf.config.experimental.list_physical_devices('GPU')
for gpu in gpus:
    # tf.config.experimental.set_memory_growth(gpu, True)
    tf.config.experimental.set_virtual_device_configuration(
        gpu,
        [tf.config.experimental.VirtualDeviceConfiguration(memory_limit=4096)]
    )

# задаем значения переменных окружения
os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'
os.environ["CUDA_VISIBLE_DEVICES"] = '-1'

# порядок индексов для перестановки ракурсов левой ноги
index = [4, 3, 2, 1, 0, 7, 6, 5]

# допустимые разсширения имен файлов с изображениями
ALLOWED_EXTENSIONS = ['zip']

# имя каталога для загрузки изображений
UPLOAD_FOLDER = 'inputs/'
RESULT_FOLDER = 'result/'

# имя ZIP-архива с изображениями
output_archive_file_path = os.path.join(RESULT_FOLDER, 'images.zip')

# имя файла с моделью
model_file_path = 'data/model/M_good190.h5'

# загружаем модель
model = load_model(model_file_path, compile=False)

# создаем каталог для изображений, если он не существует
if not os.path.exists(UPLOAD_FOLDER):
    os.makedirs(UPLOAD_FOLDER)

# создаем WSGI приложение
app = Flask(__name__)
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['RESULT_FOLDER'] = RESULT_FOLDER

# создаем API-сервер
api = Api(app)

# создаем парсер API-запросов
parser = reqparse.RequestParser()
parser.add_argument('zip_file', type=werkzeug.datastructures.FileStorage, help='ZIP archive of images (zip)', location='files', required=True)
parser.add_argument('mirr', type=str, help='Тип (`mirr1` или `mirr2`)', required=True)


def allowed_file(file_name):
    """
    Функция проверки расширения файла
    """
    return '.' in file_name and file_name.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS


@api.route('/images')
@api.produces(['application/zip'])
class Images(Resource):

    # если POST-запрос
    @api.expect(parser)
    def post(self):

        try:
            # определяем текущее время
            start_time = time.time()

            # получаем параметры запроса
            args = parser.parse_args()

            # получаем тип (mirr или mirr2)
            mirr = args['mirr']

            # проверка входного параметра
            if mirr not in ['mirr1', 'mirr2']:
                raise ValueError('Not allowed value of "mirr" parameter')

            # проверка наличия файла в запросе
            if 'zip_file' not in request.files:
                raise ValueError('No input ZIP file')

            # получаем файл из запроса
            f = request.files['zip_file']

            # проверка на наличие имени у файла
            if f.filename == '':
                raise ValueError('Empty ZIP file name')

            # проверка на допустимое расширение файла (zip)
            if not allowed_file(f.filename):
                raise ValueError('Upload ZIP file (zip)')

            # имя входного ZIP-файла с изображениями
            input_archive_file_name = secure_filename(f.filename)

            # полный путь для сохранения ZIP-файла
            input_archive_file_path = os.path.join(UPLOAD_FOLDER, input_archive_file_name)

            # сохраняем ZIP-файл
            f.save(input_archive_file_path)

            # временный каталог с 8-ю файлами - исходными картинками
            #input_dir_path = input_archive_file_path.replace('.zip', '')
            input_dir_path = os.path.join(app.config['RESULT_FOLDER'], input_archive_file_name.split('.')[0])

            # создаем каталог для изображений, если он не существует
            if not os.path.exists(input_dir_path):
                os.makedirs(input_dir_path)

            # распаковываем ZIP-архив во временный каталог
            with zipfile.ZipFile(input_archive_file_path, 'r') as z:
                z.extractall(input_dir_path)

            # удаляем полученный ZIP-файл
            if os.path.exists(input_archive_file_path):
                os.remove(input_archive_file_path)

            # сортируем по алфавиту
            names = sorted(os.listdir(input_dir_path))

            # для левой ноги, переставляем по ракурсам
            if mirr == 'mirr2':
                names = [names[i] for i in index]

            # получаем изображение-массив np.array shape:(1, 2048, 2048, 8)
            img = get_img_for_predict(names, mirr, input_dir_path)

            # генерируем новое изображениее-массив - np.array shape:(2048, 2048, 8)
            img = predict_img(model, img)

            # получаем список 8 изображений всех ракурсов
            list_img1 = np.split(img, indices_or_sections=8, axis=-1)

            # очистка изображений от мелких деталей
            list_img = list(map(lambda x: clear_artef(np.uint8(x.reshape(2048,2048))), list_img1))

            # сохраняем изображения в ZIP-архив
            save_gen_img(list_img, RESULT_FOLDER, names, mirr, output_archive_file_path)

            # создаем каталог для изображений, если он не существует
            if os.path.exists(input_dir_path):
                shutil.rmtree(input_dir_path)

            # отправка ZIP-архива с изображениями
            return send_file(output_archive_file_path, as_attachment=True, download_name=os.path.basename(output_archive_file_path), mimetype='application/zip')

        except ValueError as err:
            return {
                'error': err.args[0],
                'filename': f.filename,
                'time': (time.time() - start_time)
            }

        except:
            return {
                'error': 'Unknown error',
                'time': (time.time() - start_time)
            }


# запускаем сервер на порту 8008 (или на любом другом свободном порту)
if __name__ == '__main__':
    app.run(debug=False, port=8008)
