<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Exports\ProductExport;
use Excel;

class PostController extends Controller
{
    public function exportProductToExcel()
    {
        return Excel::download(new ProductExport, 'productList.xlsx');
    }

    public function exportToCSV()
    {
        return Excel::download(new ProductExport, 'productList.csv');
    }

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

            // Get product Tags
            if ($product->product_tags === '') {
                $productTagsArray = [];
            } else {
                $productTagsArray = $this->filterTags($product->product_tags);
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
                'product_tags' => $productTagsArray,
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

                    // Get product Tags
                    if ($product->product_tags === '') {
                        $productTagsArray = [];
                    } else {
                        $productTagsArray = $this->filterTags($product->product_tags);
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
                    $productArray['product_tags'] = $productTagsArray;
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
                    $productArray['soft_delete'] = $product->soft_delete;

                    // Send back response to client
                    return response()->json(['status_code' => '200', 'status' => 'success', 'returned_items' => $rowCount, 'message' => array(), 'data' => $productArray]);
                } else {
                    // Product not found for given id
                    return response()->json(['status_code' => '404', 'status' => 'error', 'message' => array('Product not found in records')]);
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
    * @param DATE-CREATED: 14/2/2022
    * @param AUTHOR: Dennis Oteino
    * @param DESCRIPTION: This function create a new product for both vendors and system owner.
    */
    public function createNewProduct(Request $request, $id=false)
    {
        try {
            // Generate product unique ID
            $productId = '_id-'.uniqid(rand(10000, 99999));

            // Generate product stock keeping unit(SKU)
            $productSKU = rand(10000, 99999);

            // Get shop name
            if ($id) {
                $vendor = DB::table('ptz_account_users')->where(['id' => $id])->first();
                $shopName = $vendor->store_name;
                $vendorID = $vendor->vendor_id;
            } else {
                $shopName = 'Official Store';
                $vendorID = 'OFVID001';
            }

            // Create a product slug
            $slug = strtolower(str_replace(' ', '-', $request->product_title));

            //Get incoming data from the inputs
            $inputData = array();
            $inputData['product_id'] = $productId;
            $inputData['vendor_id'] = $vendorID;
            $inputData['product_title'] = $request->product_title;
            $inputData['shop_name'] = $shopName;
            $inputData['slug'] = $slug;
            $inputData['brand_id'] = $request->brand_id;
            $inputData['category_id'] = $request->category_id;
            $inputData['subcategory_id'] = $request->subcategory_id;
            $inputData['sub_subcategory_id'] = $request->sub_subcategory_id;
            $inputData['product_tags'] = $request->product_tags;
            $inputData['product_sku'] = $productSKU;
            $inputData['product_qty'] = $request->product_qty;
            $inputData['cost_price'] = $request->cost_price;
            $inputData['selling_price'] = $request->selling_price;
            $inputData['discount_price'] = $request->cost_price;
            $inputData['percentage'] = $request->percentage;
            $inputData['product_size'] = $request->product_size;
            $inputData['product_color'] = $request->product_color;
            $inputData['product_material'] = $request->product_material;
            $inputData['product_thumbnail'] = $request->product_thumbnail;
            $inputData['unit_size'] = $request->unit_size;
            $inputData['hot_deals'] = $request->hot_deals;
            $inputData['featured'] = $request->featured;
            $inputData['is_recomended'] = $request->is_recomended;
            $inputData['special_offer'] = $request->special_offer;
            $inputData['special_deals'] = $request->special_deals;
            $inputData['value_of_the_day'] = $request->value_of_the_day;
            $inputData['weekly_offers'] = $request->weekly_offers;
            $inputData['new_arrivals'] = $request->new_arrivals;
            $inputData['short_description'] = $request->short_description;
            $inputData['product_specification'] = $request->product_specification;
            $inputData['long_description'] = $request->long_description;

            // Validate products fields (required)
            if (empty($inputData['product_title'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'messages' => 'Please provide a product title to continue']);
            }

            if (empty($inputData['brand_id']) || !ctype_digit($inputData['brand_id'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Product brand is required and must not contain numbers.']);
            }

            if (empty($inputData['product_qty']) || !ctype_digit($inputData['product_qty'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Set a quantity for the product only numbers allowed.']);
            }

            if (empty($inputData['product_thumbnail'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Please provide a product thumbnail image.']);
            }

            if (empty($inputData['long_description'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Please provide a product description.']);
            }

            // Upload product thumbnail.
            $image = $request->file('product_thumbnail');
            $name_gen = 'patazone-product-image-'.hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
            Image::make($image)->resize(500, 500)->save('images/product_thumbnails/'.$name_gen);
            $imageUrlPath = 'images/product_thumbnails/'.$name_gen;
            $inputData['product_thumbnail'] = $imageUrlPath;

            // Save product into the database and return the id
            $product_id = DB::table('ptz_products')->insertGetId($inputData);

            // Upload product multiple images
            $this->uploadmultipleImages($request->file('multi_image'), $product_id);

            // Get the inserted product
            $product = DB::table('ptz_products')->where(['id' => $product_id])->first();

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

                // Get product Tags
                if ($product->product_tags === '') {
                    $productTagsArray = [];
                } else {
                    $productTagsArray = $this->filterTags($product->product_tags);
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
                $productArray['product_tags'] = $productTagsArray;
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
                return response()->json(['status_code' => '201', 'status' => 'success', 'massage' => ['Product created successfully.'], 'data' => $productArray]);
            }

            // Catch internal errors
        } catch (\Exception $ex) {
            return response()->json(['status_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    /**
    * @param DATE-CREATED: 14/2/2022
    * @param AUTHOR: Dennis Otieno
    * @param DESCRIPTION: This function runs multiple image upload
    */
    public function uploadmultipleImages($images, $product_id)
    {
        //
        try {
            //Loop through the image array while saving in the database.
            foreach ($images as $img) {
                $make_name = 'patazone-product-image-'.hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
                Image::make($img)->resize(500, 500)->save('images/product_multiImages/'.$make_name);
                $imgUrl = 'images/product_multiImages/'.$make_name;
                DB::table('ptz_multipleimgs')->insert([
                    'product_id' => $product_id,
                    'img_url' => $imgUrl
                ]);
            }
            return true;
        } catch (\Exception $ex) {
            return response()->json(['statue_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    public function updateMultipleImages(Request $request, $id)
    {
        try {
            $images = $request->file('multi_image');
            //Loop through the image array while saving in the database.
            $imgArray = array();
            foreach ($images as $img) {
                $make_name = 'patazone-product-image-'.hexdec(uniqid()).'.'.$img->getClientOriginalExtension();
                Image::make($img)->resize(500, 500)->save('images/product_multiImages/'.$make_name);
                $imgUrl = 'images/product_multiImages/'.$make_name;
                DB::table('ptz_multipleimgs')->insert([
                    'product_id' => $id,
                    'img_url' => $imgUrl
                ]);

                $imgArray[] = $imgUrl;
            }
            return response()->json(['status_code' => '201', 'status' => 'success', 'message' => 'Image(s) for product ID '.$id.' created successfully.', 'data' => $imgArray]);
        } catch (\Exception $ex) {
            return response()->json(['statue_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    /**
    * @param DATE-CREATED: 14/2/2022
    * @param AUTHOR: Dennis otieno
    * @param DESCRIPTION: This function is for deleting a product; soft delete should be an intager 1 by default is null.
    */
    public function deleteProduct($id, $softid=false)
    {
        try {
            // Check if a softdelete key is passed the perform the task else delete the entire product
            if ($softid) {
                if (is_numeric($softid) && $softid === '1') {
                    $datavalue = array();
                    $datavalue['soft_delete'] = $softid;
                    DB::table('ptz_products')->where(['id' => $id])->update($datavalue);
                    return response()->json(['status_code' => '200', 'status' => 'success', 'message' => 'Product soft deleted successfully']);
                } else {
                    return response()->json(['status_code' => '400', 'status' => 'error', 'message' => 'Soft delete value should be a numeric charactor and should be equal to 1']);
                }
            } else {
                // Delete product in products
                DB::table('ptz_products')->where(['id' => $id])->delete();

                // Delete product images (Multiple Images)
                $this->deleteMultipleImages($id);
                $message = 'Product with ID '.$id.' is permanently deleted from the records';
                return response()->json(['status_code' => '200', 'status' => 'success', 'message' => $message]);
            }
        } catch (\Exception $ex) {
            return response()->json(['statue_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    /**
    * @param DATE-CREATED: 15/2/2022
    * @param AUTHOR: Dennis otieno
    * @param DESCRIPTION: This function updates product details including images
    */
    public function updateProductDetails(Request $request, $id)
    {
        try {
            // Create a product slug
            $slug = strtolower(str_replace(' ', '-', $request->product_title));

            //Get incoming data from the inputs for edit
            $inputData = array();
            $inputData['product_title'] = $request->product_title;
            $inputData['slug'] = $slug;
            $inputData['brand_id'] = $request->brand_id;
            $inputData['category_id'] = $request->category_id;
            $inputData['subcategory_id'] = $request->subcategory_id;
            $inputData['sub_subcategory_id'] = $request->sub_subcategory_id;
            $inputData['product_tags'] = $request->product_tags;
            $inputData['product_qty'] = $request->product_qty;
            $inputData['cost_price'] = $request->cost_price;
            $inputData['selling_price'] = $request->selling_price;
            $inputData['discount_price'] = $request->cost_price;
            $inputData['percentage'] = $request->percentage;
            $inputData['product_size'] = $request->product_size;
            $inputData['product_color'] = $request->product_color;
            $inputData['product_material'] = $request->product_material;
            $inputData['product_thumbnail'] = $request->product_thumbnail;
            $inputData['unit_size'] = $request->unit_size;
            $inputData['hot_deals'] = $request->hot_deals;
            $inputData['featured'] = $request->featured;
            $inputData['is_recomended'] = $request->is_recomended;
            $inputData['special_offer'] = $request->special_offer;
            $inputData['special_deals'] = $request->special_deals;
            $inputData['value_of_the_day'] = $request->value_of_the_day;
            $inputData['weekly_offers'] = $request->weekly_offers;
            $inputData['new_arrivals'] = $request->new_arrivals;
            $inputData['short_description'] = $request->short_description;
            $inputData['product_specification'] = $request->product_specification;
            $inputData['long_description'] = $request->long_description;

            // Validate products fields (required)
            if (empty($inputData['product_title'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'messages' => 'Please provide a product title to continue']);
            }

            if (empty($inputData['brand_id']) || !ctype_digit($inputData['brand_id'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Product brand is required and must not contain numbers.']);
            }

            if (empty($inputData['product_qty']) || !ctype_digit($inputData['product_qty'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Set a quantity for the product only numbers allowed.']);
            }

            if (empty($inputData['product_thumbnail'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Please provide a product thumbnail image.']);
            }

            if (empty($inputData['long_description'])) {
                return response()->json(['status_code' => '401', 'status' => 'error', 'message' => 'Please provide a product description.']);
            }


            if ($image = $request->file('product_thumbnail')) {
                // Get the old image
                $product = DB::table('ptz_products')->where(['id' => $id])->first();
                $currentlySavedImage = $product->product_thumbnail;

                // Unlink the current image
                unlink($currentlySavedImage);

                // Update product thumbnail Image
                $name_gen = 'patazone-product-image-'.hexdec(uniqid()).'.'.$image->getClientOriginalExtension();
                Image::make($image)->resize(500, 500)->save('images/product_thumbnails/'.$name_gen);
                $imageUrlPath = 'images/product_thumbnails/'.$name_gen;
                $inputData['product_thumbnail'] = $imageUrlPath;
            }

            // Update product details in the database.
            DB::table('ptz_products')->update($inputData);

            // Upload product multiple images
            $this->uploadmultipleImages($request->file('multi_image'), $id);

            // Get the inserted product
            $product = DB::table('ptz_products')->where(['id' => $id])->first();

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

                // Get product Tags
                if ($product->product_tags === '') {
                    $productTagsArray = [];
                } else {
                    $productTagsArray = $this->filterTags($product->product_tags);
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
                $productArray['product_tags'] = $productTagsArray;
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
                return response()->json(['status_code' => '201', 'status' => 'success', 'massage' => ['Product updated successfully.'], 'data' => $productArray]);
            }
        } catch (\Exception $ex) {
            return response()->json(['statue_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
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
    * @param DATE-CREATED: 15/2/2022
    * @param AUTHOR: Dennis otieno
    * @param DESCRIPTION: This function deletes a single product image or multiple prooduct images
    */
    public function deleteInMultipleImages($id, $imgid=false)
    {
        try {
            if ($imgid) {
                // Get the image from the saved location and unlink
                $product = DB::table('ptz_multipleimgs')->where(['id' => $imgid, 'product_id' => $id])->first();
                unlink($product->img_url);

                // Delete product in the databse.
                DB::table('ptz_multipleimgs')->where(['id' => $imgid, 'product_id' => $id])->delete();
                return response()->json(['status_code' => '200', 'status' => 'success', 'message' => 'Image ID '.$imgid.' deleted successfully.']);
            } else {
                // Get all the images with the specifiied product ID
                $productImgs = DB::table('ptz_multipleimgs')->where(['product_id' => $id])->get();
                foreach ($productImgs as $img) {
                    unlink($img->img_url);
                }

                // Delete the images links from the database.
                DB::table('ptz_multipleimgs')->where(['product_id' => $id])->delete();
                return response()->json(['status_code' => '200', 'status' => 'success', 'message' => 'Images with IDs '.$id.' deleted successfully.']);
            }
        } catch (\Exception $ex) {
            return response()->json(['statue_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
    }

    /** ============================================================================
     *    PRODDUCT SECTIONS UPDATE ENDPOINTS
     * =============================================================================*/

    /**
    * @param DATE-CREATED: 15/2/2022
    * @param AUTHOR: Dennis otieno
    * @param DESCRIPTION: This function is for deleting single product multiple images
    */
    public function deleteMultipleImages($productId)
    {
        try {
            // Find the images and unlink them
            $oldImages = DB::table('ptz_multipleimgs')->where(['product_id' => $productId])->get();
            foreach ($oldImages as $img) {
                unlink($img->img_url);
            }

            // Delete the image links from the database
            DB::table('ptz_multipleimgs')->where(['product_id' => $productId])->delete();
            return true;
        } catch (\Exception $ex) {
            return response()->json(['statue_code' => '500','status' => 'error', 'message' => $ex->getMessage()]);
        }
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
    * @param DATE-CREATED: 14/2/2022
    * @param AUTHOR: Dennis Otieno
    * @param DESCRIPTION: This function returns material array.
    */
    public function filterTags($tagsString)
    {
        $tagsArray = explode(',', $tagsString);
        return $tagsArray;
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
