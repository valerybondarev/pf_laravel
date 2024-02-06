import time
import re
import json
import base64
import PIL
import zipfile

from flask import Flask, Response, render_template, make_response, request, Blueprint
from flask import flash, redirect, url_for, send_file
from flask_restx import Api, Resource, fields, reqparse
from werkzeug.utils import secure_filename
from werkzeug.datastructures import FileStorage
from PIL import Image

import os
import cv2
from inference import *


# допустимые разсширения имен файлов с изображениями
ALLOWED_EXTENSIONS = {'png', 'jpg', 'jpeg', 'tga', 'dds'}

# имя каталога для загрузки изображений
UPLOAD_FOLDER = 'input/'


result_folder = 'output/'

# device parameter
device = torch.device('cuda' if torch.cuda.is_available() else 'cpu')

# model downloading
def allowed_file(file_name):
    name, ext = os.path.splitext(os.path.basename(file_name))
    name = name.lower()
    ext = ext.lower()
    return '.' in file_name and ext in {'.jpg', '.jpeg', '.png'}

# создаем WSGI приложение
app = Flask(__name__)
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER

# создаем API-сервер
api = Api(app)

# список имен создаваемых файлов с изображениями
output_image_file_names = ['gen.jpg']


# создаем парсер API-запросов
parser = api.parser()
parser.add_argument('image_file', type=FileStorage, help='Binary Image in png format (`png`, `jpg`, `jpeg`, `tga`, `dds`)', location='files', required=True)

@api.route('/images', methods=['GET', 'POST'])

@api.produces(['/application'])
class Images(Resource):

    # если POST-запрос
    @api.expect(parser)
    def post(self):
        args = parser.parse_args()

        # определяем текущее время
        start_time = time.time()

        # проверка на допустимое расширение файла (png, jpg, jpeg)
        if not allowed_file(args.image_file.filename):
            raise ValueError('Upload an image in one of the formats (png,jpeg,jpg)')

        # получаем обработанное изображение
        img = inference_image(args.image_file)

        response = Response(img, mimetype=args.image_file.mimetype)
        response.headers["Content-Disposition"] = "attachment; filename=%s" % args.image_file.filename
        return response

# запускаем сервер на порту 8008 (или на любом другом свободном порту)
if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True, port=80)
