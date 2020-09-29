<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */
Route::post('send-email', 'AuthController@sendEmail');
Route::post('forget-password', 'AuthController@resetPassword');

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login')->name('login');
    Route::group(['middleware' => ['jwt.verify']], function ($router) {
        Route::post('logout', 'AuthController@logout');
    });
});

Route::get('/get-all-contact-us', 'FormsController@get_all_contact');
Route::get('/get-all-maintenance-quotation', 'FormsController@get_all_maintenance_quotation');
Route::get('/get-all-request-quotation', 'FormsController@get_all_request_quotation');

Route::post('/action-contact-us', 'FormsController@action_contact');
Route::post('/action-maintenance-quotation', 'FormsController@action_maintenance_quotation');
Route::post('/action-request-quotation', 'FormsController@action_request_quotation');

Route::post('contact-us', 'FormsController@contact_us');
Route::post('maintenance-quotation', 'FormsController@maintenance_quotation');
Route::post('request-quotation', 'FormsController@request_quotation');

Route::group(['prefix' => 'Image'], function () {
    Route::get('get_image_by_id/{i}', 'ImageController@get_image_by_id');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'ImageController@create_image');
            Route::post('update/{i}', 'ImageController@update_image');
        }
    );
});
Route::group(['prefix' => 'Slider'], function () {
    Route::get('get_slider_by_type/{i}', 'SliderController@get_slider');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'SliderController@create_slider');
            Route::post('update/{i}', 'SliderController@update_slider');
            Route::delete('delete/{i}', 'SliderController@delete_slider');
        }
    );
});
Route::group(['prefix' => 'About'], function () {
    Route::group(['prefix' => 'Carousel'], function () {
        Route::get('get_about_carousel', 'AboutCarouselController@get_carousel');
        Route::group(
            ['middleware' => ['jwt.verify']],
            function () {
                Route::post('create', 'AboutCarouselController@create_carousel');
                Route::post('update/{i}', 'AboutCarouselController@update_carousel');
                Route::delete('delete/{i}', 'AboutCarouselController@delete_carousel');
            }
        );
    });
    Route::group(['prefix' => 'Image'], function () {
        Route::get('get_about_image_by_position/{i}', 'AboutImageController@get_image');
        Route::group(
            ['middleware' => ['jwt.verify']],
            function () {
                Route::post('create', 'AboutImageController@create_image');
                Route::post('update/{i}', 'AboutImageController@update_image');
                Route::delete('delete/{i}', 'AboutImageController@delete_image');
            }
        );
    });
    Route::group(['prefix' => 'Video'], function () {
        Route::get('get_about_video_by_type/{i}', 'AboutVideoController@get_video');
        Route::group(
            ['middleware' => ['jwt.verify']],
            function () {
                Route::post('create', 'AboutVideoController@create_video');
                Route::post('update/{i}', 'AboutVideoController@update_video');
                Route::delete('delete/{i}', 'AboutVideoController@delete_video');
            }
        );
    });
    Route::group(['prefix' => 'Text'], function () {
        Route::get('get_about_text_by_position/{i}', 'AboutTextController@get_text');
        Route::group(
            ['middleware' => ['jwt.verify']],
            function () {
                Route::post('create', 'AboutTextController@create_text');
                Route::post('update/{i}', 'AboutTextController@update_text');
                Route::delete('delete/{i}', 'AboutTextController@delete_text');
            }
        );
    });
});
Route::group(['prefix' => 'Category'], function () {
    Route::get('get_parent_categories/{i}', 'CategoriesController@get_parent_cat');
    Route::get('get_sub_categories/{i}', 'CategoriesController@get_sub_cat');
    Route::get('get_category_by_id/{i}', 'CategoriesController@get_cat_by_id');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'CategoriesController@create_Category');
            Route::post('update/{i}', 'CategoriesController@update_Category');
            Route::delete('delete/{i}', 'CategoriesController@delete_Category');
        }
    );
});
Route::group(['prefix' => 'Tags'], function () {
    Route::get('get_tags_by_category_id/{i}', 'TagsController@get_tags');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'TagsController@create_tags');
            Route::post('update/{i}', 'TagsController@update_tags');
            Route::delete('delete/{i}', 'TagsController@delete_tags');
        }
    );
});
Route::group(['prefix' => 'Product'], function () {
    Route::post('get_products_by_category', 'ProductController@get_products_by_cat');
    Route::get('get_product_by_id/{i}', 'ProductController@get_product_by_id');
    Route::get('get_product', 'ProductController@get_product');
    Route::get('get_product_by_type/{i}', 'ProductController@get_product_by_type');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'ProductController@create_product');
            Route::post('update/{i}', 'ProductController@update_product');
            Route::delete('delete/{i}', 'ProductController@delete_product');
        }
    );
    Route::group(['prefix' => 'Image'], function () {
        Route::get('get_product_images/{i}', 'ProductImageController@get_product_images');
        Route::group(
            ['middleware' => ['jwt.verify']],
            function () {
                Route::post('create', 'ProductImageController@create_images_product');
                Route::post('update/{i}', 'ProductImageController@update_images_product');
                Route::post('delete', 'ProductImageController@delete_images_product');
            }
        );
    });
    Route::group(['prefix' => 'Tag'], function () {
        Route::get('get_products_by_tag/{i}', 'ProductTagController@get_products_by_tag');
        Route::group(
            ['middleware' => ['jwt.verify']],
            function () {
                Route::post('create', 'ProductTagController@create_tag_product');
                Route::post('update/{i}', 'ProductTagController@update_tag_product');
                Route::delete('delete/{i}', 'ProductTagController@delete_tag_product');
            }
        );
    });
});
Route::group(['prefix' => 'Service'], function () {
    Route::get('get_service', 'ServiceController@get_service');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'ServiceController@create_service');
            Route::post('update', 'ServiceController@update_service');
            Route::delete('delete/{i}', 'ServiceController@delete_service');
        }
    );
});
Route::group(['prefix' => 'Event'], function () {
    Route::get('get_events/{position}', 'EventController@get_events');
    Route::get('get_event_by_id/{position}/{i}', 'EventController@get_event_by_id');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'EventController@create_event');
            Route::put('update', 'EventController@update_event');
            Route::delete('delete/{i}', 'EventController@delete_event');
        }
    );
});
Route::group(['prefix' => 'Client'], function () {
    Route::get('get_clients/{position}/{type}', 'ClientController@get_client');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'ClientController@create_client');
            Route::put('update', 'ClientController@update_client');
            Route::delete('delete/{i}', 'ClientController@delete_client');
        }
    );
});
Route::group(['prefix' => 'Gallery'], function () {
    Route::post('get_gallery', 'GalleryController@get_gallery');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'GalleryController@create_gallery');
            Route::delete('delete/{i}', 'GalleryController@delete_gallery');
        }
    );
});
Route::group(['prefix' => 'Offer'], function () {
    Route::get('get_offers/{position}/{type}', 'OfferController@get_offer');
    Route::group(
        ['middleware' => ['jwt.verify']],
        function () {
            Route::post('create', 'OfferController@create_offer');
            Route::put('update', 'OfferController@update_offer');
            Route::delete('delete/{i}', 'OfferController@delete_offer');
        }
    );
});
