<?php

namespace App\Domain\Catalog\Services;

use App\Domain\Application\File\DTO\FileDTO;
use App\Domain\Application\File\Entities\File;
use App\Domain\Application\File\Services\FileService;
use App\Domain\Application\Helpers\ImportExcelHelper;
use App\Domain\Catalog\Enums\CatalogColumnIndexes;
use App\Domain\Catalog\Repositories\BrandRepository;
use App\Domain\Catalog\Repositories\CategoryRepository;
use App\Domain\Catalog\Repositories\ProductRepository;
use App\Domain\Catalog\Repositories\SubcategoryRepository;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Log;

class CatalogService
{
    public function __construct(
        private FileService           $fileService,
        private CategoryRepository    $categoryRepository,
        private CategoryService       $categoryService,
        private SubcategoryRepository $subcategoryRepository,
        private SubcategoryService    $subcategoryService,
        private ProductRepository     $productRepository,
        private ProductService        $productService,
        private BrandRepository       $brandRepository,
        private BrandService          $brandService
    )
    {
    }

    public function handleCatalog(File $file)
    {
        $realPath = $this->fileService->getRealPath(FileDTO::createFromFile($file));
        $importExcelHelper = new ImportExcelHelper($realPath);
        $columnNames = array_values(CatalogColumnIndexes::labels());
        $products = $importExcelHelper->getColumnsByNames($columnNames, [], false);
        return $this->saveImportedUsers($products);
    }

    public function saveImportedUsers($importedProducts)
    {
        $statistics = ['successCreatedCount' => 0, 'successUpdatedCount' => 0, 'errorCount' => 0];
        $indexItem = 0;
        foreach ($importedProducts as $productIndex => $importedProduct) {
            //dd($importedProduct);
            $productParams = $this->getNamedObject($importedProduct);

            if ($productParams['title']) {
                $productParams['title'] = trim(strip_tags($productParams['title']));
            }

            if ($productParams['stock']) {
                $productParams['stock'] = str_replace(',', '.', $productParams['stock']);
                $productParams['stock'] = $productParams['stock'] * 100;
            }

            if ($productParams['price']) {
                $productParams['price'] = str_replace(',', '.', $productParams['price']);
                $productParams['price'] = $productParams['price'] * 100;
            }

            if ($productParams['imported_at']) {
                $productParams['imported_at'] = str_replace('/', '-', $productParams['imported_at']);
                $myDateTime = DateTime::createFromFormat("d-m-Y", $productParams['imported_at']);
                $productParams['imported_at'] = $myDateTime->format("Y-m-d");//, strtotime($productParams['imported_at']));
                //dd($productParams['imported_at']);
            }

            /** Начало сохранения категории*/
            $categoryKey = CatalogColumnIndexes::dbProductKeys()[CatalogColumnIndexes::CATEGORY];
            $categoryTitle = trim($productParams[$categoryKey]);
            $category = $this->categoryRepository->getByTitle($categoryTitle);
            if (!$category) {
                $category = $this->categoryService->create(['title' => $categoryTitle]);
            }
            /** Конец сохранения категории*/

            /** Начало сохранения подкатегории*/
            $subcategoryKey = CatalogColumnIndexes::dbProductKeys()[CatalogColumnIndexes::SUBCATEGORY];
            $subcategoryTitle = trim($productParams[$subcategoryKey]);
            $subcategory = $this->subcategoryRepository->getByTitle($subcategoryTitle, $category->id);
            if (!$subcategory) {
                $subcategory = $this->subcategoryService->create(['title' => $subcategoryTitle, 'category_id' => $category->id]);
            }
            $productParams['subcategory_id'] = $subcategory->id;
            /** Конец сохранения подкатегории*/

            /** Начало сохранения бренда*/
            //$brandKey = CatalogColumnIndexes::dbProductKeys()[CatalogColumnIndexes::BRAND];
            //$brandTitle = trim($productParams[$brandKey]);
            //$brand = $this->brandRepository->getByTitle($brandTitle);
            //if (!$brand) {
            //    $brand = $this->brandService->create(['title' => $brandTitle]);
            //}
            //$productParams['brand_id'] = $brand->id;
            /** Конец сохранения бренда*/
            $productIdInCatalog = CatalogColumnIndexes::dbProductKeys()[CatalogColumnIndexes::ID_IN_CATALOG];
            $product = $this->productRepository->getByIdInCatalog($productParams[$productIdInCatalog]);
            try {
                if ($product) {
                    $statistics['successUpdatedCount']++;
                    $this->productService->update($product, $productParams);
                } else {
                    $statistics['successCreatedCount']++;
                    $this->productService->create($productParams);
                }
            } catch (\Throwable $e) {
                $statistics['errorCount']++;
                dd($productParams);
                dd($e->getMessage(), $e->getTraceAsString());
                Log::channel('catalog')->error("Error add product with id_in_catalog = $product->id_in_catalog");
            }
            $indexItem++;
        }

        return $statistics;
    }

    /**
     * Description преобразует входящий массив с числовыми индексами полученный из excel, в ассоциативный массив
     * @param $product
     * @return array
     */
    public function getNamedObject($product): array
    {
        $result = [];
        foreach (CatalogColumnIndexes::dbProductKeys() as $key => $dbProductKey) {
            $index = CatalogColumnIndexes::getIndexByConst($key);
            $result[$dbProductKey] = $product[$index];
        }
        return $result;
    }
}
