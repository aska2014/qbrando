'use strict';

/* Services */


// Demonstrate how to register services
// In this case it is a simple value service.
angular.module('qbrando.services', []).

    factory('Products', ['$resource', function($resource) {

        var resource = $resource('/product/:id');

        // Array of full information products..
        var fullInfoProducts = [];

        // Partial including (title, image, price)
        var partialInfoProducts = [];

        function search( products, id )
        {
            for(var i = 0;i < products.length; i++)
            {
                if(products[i].id == id) return products[i];
            }

            return null;
        }

        function request( id, callback )
        {
            var product = resource.get({'id': id}, callback);

            // Push the retrieved product to the loaded products
            fullInfoProducts.push(product);

            return product
        }

        return {

            'addPartialInfo': function(product)
            {
                partialInfoProducts.push(product);
            },

            /**
             * @param id
             * @param callback
             * @returns product
             */
            'getPartialInfo': function(id, callback)
            {
                // Search in full information products
                var product = search(fullInfoProducts, id);
                if(product == null)
                {
                    // Search partial information
                    product = search(partialInfoProducts, id);

                    if(product == null)
                    {
                        return request(id, callback);
                    }
                }

                callback(product);

                return product;
            },

            /**
             * @param id
             * @param callback
             * @returns product
             */
            'getFullInfo': function(id, callback)
            {
                // First try to get the product from the full loaded products
                var product = search(fullInfoProducts, id);
                if(product == null)
                {
                    return request(id, callback);
                }

                callback(product);

                return product;
            }
        };
    }]).

    // Shared variables
    factory('ModalProduct', [function () {

        return {
            // Initialize with partial information
            'setProduct': function( p ) {

                this.product = p;
            },

            'open': function() {
                $('#productModal').modal({
                    width:1000
                });
            },

            'product': {}
        };
    }])

    .factory('Cart', ['$resource', '$cookieStore', function( $resource, $cookieStore ) {

        var resource = $resource('/cart/:id');

        var isReady = false;

        // Request cart products
        var products = resource.query(function()
        {
            isReady = true;
        });

        function getFullProductsWithoutQuantity()
        {
            var newProducts = [];

            for(var i = 0;i < products.length; i++)
            {
                for(var j = 0; j < products[i].quantity; j++)
                {
                    newProducts.push(products[i]);
                }
            }

            return newProducts;
        }

        function compareByPrice(a,b) {

            if(a.price < b.price) return -1;

            if(a.price > b.price) return 1;

            return 0;
        }

        // Everything is done locally but saved to server just before user leaves the page
        var cart = {

            'save': function() {
                $cookieStore.put('products', products);
            },

            // Get all products
            'get': function() { return products; },

            // Get total number of products in cart
            'total': function() {

                var total = 0;

                for(var i = 0;i < products.length; i++)
                {
                    total += products[i].quantity;
                }

                return total;
            },

            // Add new product to cart if it's not already in their
            'add': function( product, quantity )
            {
                // Set value of quantity to the given or 1
                product.quantity = typeof quantity !== 'undefined' ? quantity : 1;

                if(! cart.has(product)) {

                    products.push(product);
                }

                cart.save();
            },

            // Check if cart has product
            'has': function(product) {

                if(product == null) return false;

                for(var i = 0; i < products.length; i++)
                {
                    if(product.id == products[i].id) return true;
                }

                return false;
            },

            // Remove product from cart
            'remove': function(product) {

                for(var i = 0; i < products.length; i++)
                {
                    if(products[i].id == product.id) products.splice(i, 1);
                }

                cart.save();
            },

            'isEmpty': function()
            {
                return products.length == 0;
            },

            'isReady': function()
            {
                return isReady;
            },

            'price': {
                'subTotal': function(product)
                {
                    return product.price * product.quantity;
                },

                'total': function()
                {
                    var total = 0;

                    for(var i = 0; i < products.length; i++)
                    {
                        total += cart.price.subTotal(products[i]);
                    }

                    return total;
                },

                'totalAfterOffer': function()
                {
                    var price = cart.price.total();

                    var fullProducts = getFullProductsWithoutQuantity();

                    fullProducts.sort(compareByPrice);

                    var freeProducts = fullProducts.slice(0, cart.price.getNumberOfOfferItems());

                    for(var i = 0; i < freeProducts.length; i++)
                    {
                        price -= freeProducts[i].price;
                    }

                    return price;
                },

                'hasOffer': function()
                {
                    return cart.price.getNumberOfOfferItems() > 0;
                },

                'getNumberOfOfferItems': function()
                {
                    return parseInt(((cart.total() - 1) / cart.price.offerItems()));
                },

                'offerItems': function()
                {
                    return 2;
                }
            }

        };


        return cart;
    }]);