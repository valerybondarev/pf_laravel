import time, os, cv2
import numpy
from flask import Flask, Response
from flask_restx import Api, Resource
from werkzeug.utils import secure_filename
from werkzeug.datastructures import FileStorage

os.environ['TF_CPP_MIN_LOG_LEVEL'] = '2'

# проверка расширения файла
def allowed_file(file_name):
    name, ext = os.path.splitext(os.path.basename(file_name))
    name = name.lower()
    ext = ext.lower()
    return '.' in file_name and ext in {'.jpg', '.jpeg', '.png'}

def add_affix(filename, affix='_NORM'):
    name, extension = os.path.splitext(filename)
    return name + affix + extension

# создаем WSGI приложение
app = Flask(__name__)

# создаем API-сервер
api = Api(app)

# создаем парсер API-запросов
parser = api.parser()
parser.add_argument('image_file', type=FileStorage, help='Binary Image in png format (`png`, `jpg`, `jpeg`, `tga`, `dds`)', location='files', required=True)
parser.add_argument('ModelPhone', required=True, location='form')

current_path = os.path.dirname(os.path.realpath(__file__))

@api.route('/images', methods=['POST'])

@api.produces(['/application'])
class Images(Resource):
    # если POST-запрос
    @api.expect(parser)
    def post(self):
        args = parser.parse_args()
        try:
            # определяем текущее время
            start_time = time.time()

            # устанавливаем переменные mtx1...mtx4, dist1...dist5 в зависимости от значения model_phone
            if args.ModelPhone == 'iPhone10.3':
                mtx1, mtx2, mtx3, mtx4 = 2.88431087e+03, 2.88140146e+03, 1.15587042e+03, 1.53591670e+03
                dist1, dist2, dist3, dist4, dist5 = 6.e-02, -9.e-02, 3.e-03, 1.e-04, -4.e-01
            else:
                raise ValueError('Unknown Camera')

            # проверка на допустимое расширение файла (png, jpg, jpeg, tga, dds)
            if not allowed_file(args.image_file.filename):
                raise ValueError('Upload an image in one of the formats (png,jpeg,jpg)')


            # применяем алгоритм
            img = cv2.imdecode(numpy.fromfile(args.image_file, numpy.uint8), cv2.IMREAD_ANYCOLOR)
            h_,  w_ = img.shape[:2]

            mtx = numpy.array([[mtx1, 0.0, mtx3],
                            [0.0, mtx2, mtx4],
                            [0.0, 0.0, 1.0]])
            dist = numpy.array([[ dist1, dist2,  dist3,  dist4, dist5]])

            newcameramtx, roi = cv2.getOptimalNewCameraMatrix(mtx, dist, (w_,h_), 1, (w_,h_))
            dst1 = cv2.undistort(img, mtx, dist, None, newcameramtx)

            # накладываем сетку, если нужно
            if args.image_file.filename.startswith('0_'):
                grid = cv2.imread(os.path.join(current_path, 'orig_setka.png'))
                dst1 = cv2.addWeighted(dst1,0.7,grid,0.3,0)

            succ, img = cv2.imencode(add_affix(os.path.basename(args.image_file.filename)), dst1)
            response = Response(img.tobytes(), mimetype=args.image_file.mimetype)
            response.headers["Content-Disposition"] = "attachment; filename=%s" % args.image_file.filename
            return response
        except ValueError as err:
            dict_response = {
                'error': err.args[0],
                'filename': args.image_file.filename,
                'time': (time.time() - start_time)
            }
            return dict_response


# запускаем сервер на порту 8008 (или на любом другом свободном порту)
if __name__ == '__main__':
    app.run(host='0.0.0.0', debug=True, port=80)


