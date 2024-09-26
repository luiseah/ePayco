<?php
/* @noinspection ALL */
// @formatter:off
// phpcs:ignoreFile

/**
 * A helper file for Laravel, to provide autocomplete information to your IDE
 * Generated for Laravel 11.24.1.
 *
 * This file should not be included in your code, only analyzed by your IDE!
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 * @see https://github.com/barryvdh/laravel-ide-helper
 */

namespace App\Facades {
            /**
     * 
     *
     */        class Api {
                    /**
         * 
         *
         * @param string $success
         * @param string $cod_error
         * @param string $message_error
         * @param array $data
         * @return \App\Services\Responses\ApiResponse 
         * @static 
         */        public static function response($success, $cod_error, $message_error, $data)
        {
                        /** @var \App\Services\Responses\ApiResponse $instance */
                        return $instance->response($success, $cod_error, $message_error, $data);
        }
            }
            /**
     * 
     *
     */        class Customer {
                    /**
         * Register a new customer.
         * 
         * This method registers a new customer in the system after validating the provided attributes.
         * It ensures that all necessary fields are provided and that they meet the specified rules.
         * If the registration is successful, it returns a positive API response with the customer data.
         * If an error occurs, an API response with the error message is returned.
         *
         * @param array $attributes Array of customer attributes (document ID, name, email, phone).
         * @return \App\Services\Responses\ApiResponse The API response containing the result of the operation.
         * @static 
         */        public static function register($attributes)
        {
                        /** @var \App\Services\CustomerService $instance */
                        return $instance->register($attributes);
        }
            }
            /**
     * 
     *
     */        class Wallet {
                    /**
         * 
         *
         * @param array $attributes
         * @return mixed 
         * @static 
         */        public static function recharge($attributes)
        {
                        /** @var \App\Services\WalletService $instance */
                        return $instance->recharge($attributes);
        }
                    /**
         * 
         *
         * @param \Customer $customer
         * @return \Wallet 
         * @static 
         */        public static function create($customer)
        {
                        /** @var \App\Services\WalletService $instance */
                        return $instance->create($customer);
        }
            }
    }

namespace Illuminate\Http {
            /**
     * 
     *
     */        class Request {
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param array $rules
         * @param mixed $params
         * @static 
         */        public static function validate($rules, ...$params)
        {
                        return \Illuminate\Http\Request::validate($rules, ...$params);
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestValidation()
         * @param string $errorBag
         * @param array $rules
         * @param mixed $params
         * @static 
         */        public static function validateWithBag($errorBag, $rules, ...$params)
        {
                        return \Illuminate\Http\Request::validateWithBag($errorBag, $rules, ...$params);
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $absolute
         * @static 
         */        public static function hasValidSignature($absolute = true)
        {
                        return \Illuminate\Http\Request::hasValidSignature($absolute);
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @static 
         */        public static function hasValidRelativeSignature()
        {
                        return \Illuminate\Http\Request::hasValidRelativeSignature();
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @param mixed $absolute
         * @static 
         */        public static function hasValidSignatureWhileIgnoring($ignoreQuery = [], $absolute = true)
        {
                        return \Illuminate\Http\Request::hasValidSignatureWhileIgnoring($ignoreQuery, $absolute);
        }
                    /**
         * 
         *
         * @see \Illuminate\Foundation\Providers\FoundationServiceProvider::registerRequestSignatureValidation()
         * @param mixed $ignoreQuery
         * @static 
         */        public static function hasValidRelativeSignatureWhileIgnoring($ignoreQuery = [])
        {
                        return \Illuminate\Http\Request::hasValidRelativeSignatureWhileIgnoring($ignoreQuery);
        }
            }
    }


namespace  {
            class Api extends \App\Facades\Api {}
            class Customer extends \App\Facades\Customer {}
            class Wallet extends \App\Facades\Wallet {}
    }





