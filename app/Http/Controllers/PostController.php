<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class PostController extends Controller
{
    /**
    * @param DATE-CREATED: 12/2/2022
    * @param AUTHOR: Dennis Otieno
    * @param DESCRIPTION: This function returns app products from the database
    */
    public function get_products()
    {
        try {
            // Get all products form the database
            $products = DB::table('ptz_products')->get();
            $rowCount = count($products);
            $newProductArray = array();
            foreach ($products as $product):
            // Get product color
        if ($product->product_color === '') {
            $productColorArray = [];
        } else {
            $productColorArray = $this->filterColor($product->product_color);
        }

            // Get product size
            if ($product->product_size === '') {
                $productSizeArray = [];
            } else {
                $productSizeArray = $this->filterSize($product->product_size);
            }

            // Get product material
            if ($product->product_material === '') {
                $productMaterialArray = [];
            } else {
                $productMaterialArray = $this->filterMaterial($product->product_material);
            }

            $imagesArray = $this->getProductImages($product->id);

            $newProductArray[] = array(
                'id' => $product->id,
                'product_id' => $product->product_id,
                'vendor_id' => $product->vendor_id,
                'product_title' => $product->product_title,
                'shop_name' => $product->shop_name,
                'slug' => $product->slug,
                'brand_id' => $product->brand_id,
                'category_id' => $product->category_id,
                'subcategory_id' => $product->subcategory_id,
                'sub_subcategory_id' => $product->sub_subcategory_id,
                'product_tags' => $product->product_tags,
                'product_sku' => $product->product_sku,
                'product_qty' => $product->product_qty,
                'cost_price' => $product->cost_price,
                'selling_price' => $product->selling_price,
                'discount_price' => $product->discount_price,
                'percentage' => $product->percentage,
                'product_size' => $productSizeArray,
                'product_color' => $productColorArray,
                'product_material' => $productMaterialArray,
                'product_thumbnail' => 'https://sellercenter.patazone.co.ke/'.$product->product_thumbnail,
                'images' => $imagesArray,
                'unit_size' => $product->unit_size,
                'hot_deals' => $product->hot_deals,
                'featured' => $product->featured,
                'is_recomended' => $product->is_recomended,
                'special_offer' => $product->special_offer,
                'special_deals' => $product->special_deals,
                'value_of_the_day' => $product->value_of_the_day,
                'weekly_offers' => $product->weekly_offers,
                'new_arrivals' => $product->new_arrivals,
                'is_varified' => $product->is_varified,
                'is_lipalater' => $product->is_lipalater,
                'short_description' => $product->short_description,
                'product_specification' => $product->product_specification,
                'long_description' => $product->long_description,
                'created_date' => $product->created_date,
                'updated_date' => $product->updated_date
            );
            endforeach;

            return response()->json(['status_code' => '200', 'status' => 'success', 'returned_items' => $rowCount,  'data' => $newProductArray]);
            //catch exception errors
        } catch (\Exception $ex) {
            return response()->json(['ststue_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    /**
    * @param DATE-CREATED:12/2/2022
    * @param AUTHOR: Dennis Otieno
    * @param DESCRIPTION: This function returns a single products details.
    */
    public function getSingleProduct(Request $request, $id = null)
    {
        try {
            if ($id != null && is_numeric($id)) {
                // Getting single product requested
                $product = DB::table('ptz_products')->where(['id' => $id])->first();

                if ($product) {
                    $rowCount = 1;
                    // Get product color
                    if ($product->product_color === '') {
                        $productColorArray = [];
                    } else {
                        $productColorArray = $this->filterColor($product->product_color);
                    }

                    // Get size array
                    if ($product->product_size === '') {
                        $productSizeArray = [];
                    } else {
                        $productSizeArray = $this->filterSize($product->product_size);
                    }

                    // Get product material
                    if ($product->product_material === '') {
                        $productMaterialArray = [];
                    } else {
                        $productMaterialArray = $this->filterMaterial($product->product_material);
                    }

                    // Get product images
                    $productImagesArray = $this->getProductImages($product->id);

                    // New product object
                    $productArray = array();
                    $productArray['id'] = $product->id;
                    $productArray['product_id'] = $product->product_id;
                    $productArray['vendor_id'] = $product->vendor_id;
                    $productArray['product_title'] = $product->product_title;
                    $productArray['shop_name'] = $product->shop_name;
                    $productArray['slug'] = $product->slug;
                    $productArray['brand_id'] = $product->brand_id;
                    $productArray['category_id'] = $product->category_id;
                    $productArray['subcategory_id'] = $product->subcategory_id;
                    $productArray['sub_subcategory_id'] = $product->sub_subcategory_id;
                    $productArray['product_tags'] = $product->product_tags;
                    $productArray['product_sku'] = $product->product_sku;
                    $productArray['product_qty'] = $product->product_qty;
                    $productArray['cost_price'] = $product->cost_price;
                    $productArray['selling_price'] = $product->selling_price;
                    $productArray['discount_price'] = $product->discount_price;
                    $productArray['percentage'] = $product->percentage;
                    $productArray['product_size'] = $productSizeArray;
                    $productArray['product_color'] = $productColorArray;
                    $productArray['product_material'] = $productMaterialArray;
                    $productArray['product_thumbnail'] = 'https://sellercenter.patazone.co.ke/'.$product->product_thumbnail;
                    $productArray['images'] = $productImagesArray;
                    $productArray['unit_size'] = $product->unit_size;
                    $productArray['hot_deals'] = $product->hot_deals;
                    $productArray['featured'] = $product->featured;
                    $productArray['is_recomended'] = $product->is_recomended;
                    $productArray['special_offer'] = $product->special_offer;
                    $productArray['special_deals'] = $product->special_deals;
                    $productArray['value_of_the_day'] = $product->value_of_the_day;
                    $productArray['weekly_offers'] = $product->weekly_offers;
                    $productArray['new_arrivals'] = $product->new_arrivals;
                    $productArray['is_varified'] = $product->is_varified;
                    $productArray['is_lipalater'] = $product->is_lipalater;
                    $productArray['short_description'] = $product->short_description;
                    $productArray['product_specification'] = $product->product_specification;
                    $productArray['long_description'] = $product->long_description;
                    $productArray['created_date'] = $product->created_date;
                    $productArray['updated_date'] = $product->updated_date;

                    // Send back response to client
                    return response()->json(['status_code' => '200', 'status' => 'success', 'returned_items' => $rowCount, 'message' => array(), 'data' => $productArray]);
                } else {
                    // Product not found for given id
                    return response()->json(['status_code' => '400', 'status' => 'error', 'message' => array('Product not found in records')]);
                }
            } elseif (!is_numeric($id)) {
                return response()->json(['status_code' => '400', 'status' => 'error', 'message' => array('Product ID must contain only numeric charactors.')]);
            }
            //catch error messages
        } catch (\Exception $ex) {
            return response()->json(['status_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    /**
     * @param DATE-CREATED: 12/2/2022
     * @param AUTHOR: Dennis Otieno
     * @param DESCRIPTION: This function runs update on a single product when the product ID is passed.
     */
    public function updateSingleProduct(Request $request, $id)
    {
        try {
            // Check if id is present
            if ($id) {
                $productData = array();
                $productData['title'] = $request->product_title;
            } else {
                return response()->json(['status_code' => '400', 'status' => 'error', 'message' => ['Bad request kindly provide ID']]);
            }
            //code...
        } catch (\Exception $ex) {
            return response()->json(['status_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    /**
    * @param DATE-CREATED: 12/2/2022
    * @param AUTHOR: Dennis Oteino
    * @param DESCRIPTION: This function create a new product with data comming from the form.
    */
    public function createNewProduct(Request $request)
    {
        try {
            //Get incoming data from the inputs
            $inputData = array();
            $inputData['product_title'] = $request->product_title;
            $inputData['product_brand'] = $request->product_brand;
            $inputData['product_qty'] = $request->product_qty;
            $inputData['product_thubnail'] = $request->product_thumbnail;
            $inputData['product_description'] = $request->product_description;

            // Validate products fields (required)
            if (empty($inputData['product_title'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'messages' => 'Please provide a product title to continue']);
            }

            if (empty($inputData['product_brand']) || ctype_digit($inputData['product_brand'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Product brand is required and must not contain numbers.']);
            }

            if (empty($inputData['product_qty']) || !ctype_digit($inputData['product_qty'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Set a quantity for the product only numbers allowed.']);
            }

            if (empty($inputData['product_thumbnail'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Please provide a product thumbnail image.']);
            }

            if (empty($inputData['product_description'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Please provide a product description.']);
            }

            // Upload product thumbnail.
            $image = $request->file('product_thumbnail');
            $name_gen = hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(720, 960)->save('images/product_thumbnails/'.$name_gen);
            $imageUrlPath = 'images/product_thumbnails/'.$name_gen;
            $inputData['product_thumbnail'] = $imageUrlPath;

            // Save product into the database and return the id
            $product_id = DB::table('ptz_products')->insertGetId($inputData);

            // Upload product multiple images
            $images = $request->file('multi_image');
            $imageUploadPath = 'images/products_multiImages/';
            $this->uploadmultipleImages($images, $imageUploadPath, $product_id);

            // Get the inserted product
            $product = DB::table('ptz_products')->where(['id' => $product_id])->get();

            // Check if the product is found.
            if (!$product) {
                return response()->json(['status_code' => '404', 'status' => 'error', 'message' => 'Product NOT found!']);
            } else {
                // Get product color
                if ($product->product_color === '') {
                    $productColorArray = [];
                } else {
                    $productColorArray = $this->filterColor($product->product_color);
                }

                // Get size array
                if ($product->product_size === '') {
                    $productSizeArray = [];
                } else {
                    $productSizeArray = $this->filterSize($product->product_size);
                }

                // Get product material
                if ($product->product_material === '') {
                    $productMaterialArray = [];
                } else {
                    $productMaterialArray = $this->filterMaterial($product->product_material);
                }

                // Get product images
                $productImagesArray = $this->getProductImages($product->id);

                // New product object
                $productArray = array();
                $productArray['id'] = $product->id;
                $productArray['product_id'] = $product->product_id;
                $productArray['vendor_id'] = $product->vendor_id;
                $productArray['product_title'] = $product->product_title;
                $productArray['shop_name'] = $product->shop_name;
                $productArray['slug'] = $product->slug;
                $productArray['brand_id'] = $product->brand_id;
                $productArray['category_id'] = $product->category_id;
                $productArray['subcategory_id'] = $product->subcategory_id;
                $productArray['sub_subcategory_id'] = $product->sub_subcategory_id;
                $productArray['product_tags'] = $product->product_tags;
                $productArray['product_sku'] = $product->product_sku;
                $productArray['product_qty'] = $product->product_qty;
                $productArray['cost_price'] = $product->cost_price;
                $productArray['selling_price'] = $product->selling_price;
                $productArray['discount_price'] = $product->discount_price;
                $productArray['percentage'] = $product->percentage;
                $productArray['product_size'] = $productSizeArray;
                $productArray['product_color'] = $productColorArray;
                $productArray['product_material'] = $productMaterialArray;
                $productArray['product_thumbnail'] = 'https://sellercenter.patazone.co.ke/'.$product->product_thumbnail;
                $productArray['images'] = $productImagesArray;
                $productArray['unit_size'] = $product->unit_size;
                $productArray['hot_deals'] = $product->hot_deals;
                $productArray['featured'] = $product->featured;
                $productArray['is_recomended'] = $product->is_recomended;
                $productArray['special_offer'] = $product->special_offer;
                $productArray['special_deals'] = $product->special_deals;
                $productArray['value_of_the_day'] = $product->value_of_the_day;
                $productArray['weekly_offers'] = $product->weekly_offers;
                $productArray['new_arrivals'] = $product->new_arrivals;
                $productArray['is_varified'] = $product->is_varified;
                $productArray['is_lipalater'] = $product->is_lipalater;
                $productArray['short_description'] = $product->short_description;
                $productArray['product_specification'] = $product->product_specification;
                $productArray['long_description'] = $product->long_description;
                $productArray['created_date'] = $product->created_date;
                $productArray['updated_date'] = $product->updated_date;

                // Return success response for product creation.
                return response()->json(['status_code' => '200', 'status' => 'success', 'massage' => ['Product created successfully.'], 'data' => $productArray]);
            }

            // Catch internal errors
        } catch (\Exception $ex) {
            return response()->json(['status_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    /**
    * @param DATE-CREATED: 12/2/2022
    * @param AUTHOR: Dennis Otieno
    * @param DESCRIPTION: This function runs multiple image upload
    */
    public function uploadmultipleImages($images, $imageUploadPath, $product_id)
    {
        //
        try {
            //Loop through the image array while saving in the database.
            foreach ($images as $img) {
                $make_name = hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
                Image::make($img)->resize(720, 960)->save($imageUploadPath.$make_name);
                DB::table('ptz_multipleimgs')->insert([
                'product_id' => $product_id,
                'img_url' => $imageUploadPath
            ]);
            }
        } catch (\Exception $ex) {
            return response()->json(['ststue_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    /**
    * @param DATE-CREATED: 12/2/2022
    * @param AUTHOR: Dennis otieno
    * @param DESCRIPTION: This function returns color array
    */
    public function filterColor($colorString)
    {
        // Get color array
        $colorArray = explode(',', $colorString);
        return $colorArray;
    }

    /**
    * @param DATE-CREATED: 12/2/2022
    * @param AUTHOR: Dennis Otieno
    * @param DESCRIPTION: This function returns sizes array
    */
    public function filterSize($sizeString)
    {
        //Get size array
        $sizeArray = explode(',', $sizeString);
        return $sizeArray;
    }

    /**
    * @param DATE-CREATED: 12/2/2022
    * @param AUTHOR: Dennis Otieno
    * @param DESCRIPTION: This function returns material array.
    */
    public function filterMaterial($materialString)
    {
        //Get material array
        $materialArray = explode(',', $materialString);
        return $materialArray;
    }

    /**
    * @param DATE-CREATED: 12/2/2022
    * @param AUTHOR: Dennis Otieno
    * @param DESCRIPTION: This function returns single product images array.
    */
    public function getProductImages($productId)
    {
        $images = DB::table('ptz_multipleimgs')->where(['product_id' => $productId])->get();
        if ($images) {
            $imagesArray = array();
            foreach ($images as $image):
                $imagesArray[] = 'https://sellercenter.patazone.co.ke/'.$image->img_url;
            endforeach;
            return $imagesArray;
        } else {
            $imagesArray = [];
            return $imagesArray;
        }
    }
}
