<?php

use ClickBank\CBItem;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\View;
use Kareem3d\Controllers\FreakController;

class FreakProductController extends FreakController {

    /**
     * @var Product
     */
    protected $products;

    /**
     * @var Category
     */
    protected $categories;

    /**
     * @var Color
     */
    protected $colors;

    /**
     * @var ClickBank\CBItem
     */
    protected $cbItems;

    /**
     * @param Product $products
     * @param Category $categories
     * @param Color $colors
     * @param ClickBank\CBItem $cbItems
     */
    public function __construct( Product $products, Category $categories, Color $colors, CBItem $cbItems )
    {
        $this->products = $products;

        $this->categories = $categories;

        $this->colors = $colors;

        $this->cbItems = $cbItems;

        $this->usePackages( 'Image' );

        $this->setExtra(array(
            'images-group-name' => 'Product.Gallery',
            'images-type'       => 'gallery',
            'image-group-name'  => 'Product.Main',
            'image-type'        => 'main',
        ));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        Asset::addPlugin('datatables');
        Asset::addPlugin('ibutton');

        $products = $this->products->get();

        return View::make('panel::products.data', compact('products'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getShow($id)
    {
        $product = $this->products->find( $id );

        $this->setPackagesData($product);

        return View::make('panel::products.detail', compact('product', 'id'));
    }

    /**
     * @return mixed
     */
    public function postAvailableMany()
    {
        $available = Input::get('Product.available');

        foreach(Input::get('Product.available_ids', array()) as $productId)
        {
            if(in_array($productId, $available))
            {
                $this->products->find($productId)->update(array(
                    'available' => true
                ));
            }
            else
            {
                $this->products->find($productId)->update(array(
                    'available' => false
                ));
            }
        }

        return Redirect::back()->with('success', 'Products availability updated successfully');
    }

    /**
     * @param $id
     */
    public function postAvailableOne($id)
    {
        $this->products->find($id)->update(array(
            'available' => Input::has('Product.available')
        ));

        return Redirect::back()->with('success', 'Product availability updated successfully');
    }

    /**
     * Show the add for creating a new resource.
     *
     * @return Response
     */
    public function getCreate()
    {
        $product = $this->products;

        $this->setPackagesData($product);

        $categories = $this->categories->all();

        $colors = $this->colors->all();

        return View::make('panel::products.add', compact('product', 'categories', 'colors'));
    }

    /**
     * Show the add for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function getEdit($id)
    {
        $product = $this->products->find( $id );

        $this->setPackagesData($product);

        return $this->getCreate()->with('product', $product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function postCreate()
    {
        // Find or get new instance of the product
        $product = $this->products->findOrNew(Input::get('insert_id'))->fill(Input::get('Product'));

        $this->setImageSEO($product);

        return $this->jsonValidateResponse($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function postEdit($id)
    {
        $product = $this->products->find($id)->fill(Input::get('Product'));

        $this->setImageSEO( $product );

        return $this->jsonValidateResponse( $product );
    }

    /**
     * @param Product $product
     */
    protected function setImageSEO( Product $product )
    {
        $this->addExtra('image-title', $product->en('title'));
        $this->addExtra('image-alt', $product->en('title'));
        $this->addExtra('images-title', $product->en('title'));
        $this->addExtra('images-alt'  , $product->en('title'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function getDelete($id)
    {
        $this->products->find($id)->delete();

        return $this->redirectBack('Product deleted successfully.');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function postClickbank($id)
    {
        $this->cbItems->createUnique(array(
            'product_id' => $id,
            'item_id' => Input::get('ClickBank.item_id')
        ));

        return $this->redirectBack('Product connected to clickbank successfully.');
    }

    /**
     * @param $id
     */
    public function postFacebook($id)
    {
        $product = $this->products->findOrFail($id);

        if(! $facebookTitle = Input::get('facebook_title'))
        {
            $facebookTitle = $product->title . PHP_EOL;

            if($product->hasOfferPrice())
            {
                $facebookTitle .= 'Special Offer <<<<<'.$product->actualPrice.' QAR>>>>>>';
            }

            else
            {
                $facebookTitle .= 'Price ' .$product->actualPrice . ' QAR';
            }
        }

        $fb = new Facebook(Config::get('facebook.config'));

        $params = array(
            "access_token" => Config::get('facebook.access_token'),
            "message" => $facebookTitle,
            'description' => $facebookTitle,
            "source" => $product->getImage('main')->getLargest()->url,
            "link" => URL::product($product)
        );

        try {
            $fb->api('/'.Config::get('facebook.page_id').'/feed', 'POST', $params);

            return Redirect::back()->with('success', 'Product has been posted to facebook successfully.');
        } catch(Exception $e) {

            dd('Eb3tly elmsg de yhoby: ' . $e->getMessage());
        }
    }
}