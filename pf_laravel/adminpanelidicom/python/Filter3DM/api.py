import time, os, werkzeug, zipfile, subprocess, cv2
from flask import Flask, render_template, make_response, request, Blueprint, send_file
from flask_restx import Api, Resource, fields, reqparse
from werkzeug.utils import secure_filename
from distutils.dir_util import copy_tree

# from PIL import Image
# from flask import flash, redirect, url_for, send_file
# import re, json, base64, cv2, PIL
# import numpy as np


# допустимые разсширения имен файлов с изображениями
ALLOWED_EXTENSIONS = {'zip', 'rar', '7z', 'tar', 'gz'}

# имя каталога для загрузки изображений
UPLOAD_FOLDER = 'inputs/'
DATA_FOLDER = 'data/'
MIRR1_FOLDER = 'mirr/1/'
MIRR2_FOLDER = 'mirr/2/'


def allowed_file(file_name):
    """
    Функция проверки расширения файла
    """
    return '.' in file_name and file_name.rsplit('.', 1)[1].lower() in ALLOWED_EXTENSIONS


def rename_files(dir, scheme='mirr1'):
    if scheme == 'mirr1':
        for file in os.listdir(dir):
            os.rename(dir+file, dir+file.replace('mirr1', 'mirr2'))


def sort_dir(dirname):
    new = list()
    for file in os.listdir(dirname):
        if file.split('.')[0].isdigit():
            new.append(file)
    return sorted(new, key=lambda f: int(f.split('.')[0]))

# создаем WSGI приложение
app = Flask(__name__)
app.config['UPLOAD_FOLDER'] = UPLOAD_FOLDER
app.config['DATA_FOLDER'] = DATA_FOLDER

# создаем API-сервер
api = Api(app)

current_path = os.path.dirname(os.path.realpath(__file__))

# создаем парсер API-запросов
parser = reqparse.RequestParser()
parser.add_argument('image_file', type=werkzeug.datastructures.FileStorage, help='Image file with a transparent background (`png`, `jpg`, `jpeg`, `tga`, `dds`)', location='files', required=True)
parser.add_argument('scale', type=werkzeug.datastructures.Accept, required=True)


@api.route('/images', methods=['GET', 'POST'])
@api.produces(['/application'])
class Images(Resource):

    # если POST-запрос
    @api.expect(parser)
    def post(self):
        # проверка наличия файла в запросе
        if 'image_file' not in request.files:
            raise ValueError('No input file')


        # получаем файл из запроса
        f = request.files['image_file']
        req_mirr = request.form.get('mirr');
        scale = request.form.get('scale')

        # проверка на наличие имени у файла
        if f.filename == '':
            raise ValueError('Empty file name')

        # проверка на допустимое расширение файла (png, jpg, jpeg, tga, dds)
        if not allowed_file(f.filename):
            raise ValueError('Upload an image in one of the formats (png, jpg, jpeg, tga, dds)')

        # имя файла
        image_file_name = secure_filename(f.filename)
        # задаем полный путь к файлу
        image_file_path = os.path.join(current_path, app.config['UPLOAD_FOLDER'], image_file_name)

        # сохраняем файл
        f.save(image_file_path)
        print('saved...')
        unzipped = os.path.join(app.config['DATA_FOLDER'], image_file_name.split('.')[0])

        # unzipping files
        with zipfile.ZipFile(image_file_path, 'r') as zip_ref:
            zip_ref.extractall(unzipped)
        print('unzipped...')

        # получаем список файлов после разархивирования, сортируем по возрастанию
        files = sort_dir(unzipped)

        # переименовываем отсортированные файлы от 0_segmap.png до n_segmap.png, где n = (количество файлов .png - 1)
        fileNumber = 0
        for filename in files:
            if filename.endswith('.png'):
                os.rename(os.path.join(unzipped, filename), os.path.join(unzipped, str(fileNumber) + '_segmap.png'))
                fileNumber += 1

        # копируем MIRR-файлы в каталог с файлами png в зависимости от значения переменной MIRR
        mirr_dir = MIRR1_FOLDER if req_mirr == 'mirr1' else MIRR2_FOLDER
        copy_tree(mirr_dir, unzipped + '/')

        command_plus = ["./vhgen ../" + unzipped]
        subprocess.run(command_plus, shell=True, cwd=os.path.join(current_path, 'bin'))

        command_convert = "python3 ply2obj.py " + unzipped + "/generated/surface_7.ply"
        subprocess.run(command_convert, shell=True)

        # переименовываем файл /generated/surface_7.obj в left.obj или right.obj в зависимости от значения переменной MIRR
        newSurfaceName = 'left.obj' if req_mirr == 'mirr2' else 'right.obj'
        os.rename(os.path.join(unzipped + "/generated/surface_7.obj"), os.path.join(unzipped + "/generated/" + newSurfaceName))

        return send_file(os.path.join(unzipped, "generated", newSurfaceName), download_name=os.path.basename(newSurfaceName), as_attachment=True, mimetype='application/octet-stream')

# запускаем сервер на порту 8008 (или на любом другом свободном порту)
if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True, port=80)

