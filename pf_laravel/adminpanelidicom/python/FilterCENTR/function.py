import os
import cv2
import numpy as np
import input_data

def read_img(dir: str) -> np.array:
  img = cv2.imread(dir, 0)[800:2848, 136:2184].astype(np.bool_).astype(np.int8)
  img[img==0] = -1
  return np.expand_dims(img, axis=2) #shape np.array (2048, 2048, 1)


def get_img_for_predict(dir_folder):
  list_img = list(map(lambda x: read_img(os.path.join(dir_folder, f'{x}.png')), input_data.RAKURSES))
  X1 = np.concatenate(list_img, axis=-1)
  return np.expand_dims(X1, axis=0) #shape np.array (1, 2048, 2048, 8)


def predict_img(img):
  img = input_data.g_model.predict(img)[0] * 127.5 + 127.5
  return np.where(img < 128, 0, 255) #shape np.array (2048, 2048, 8)


def save_gen_img(img, path):
  Z =  np.zeros((8, 3088, 2320))
  for i in range(8):
    Z[i, 800:2848, 136:2184] = img[:,:,i]
    cv2.imwrite(os.path.join(path, f'{str(i)}.png'), Z[i])
