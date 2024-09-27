<?php

namespace Tests\Unit;

use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Notifications\PaymentConfirmation;
use App\Services\WebService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class WebServiceTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_customer_registration(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');

        $ws = new WebService();

        $documentId = '123456789';
        $name = 'John Doe';
        $email = 'johndoe@example.com';
        $phone = '123456789';

        $response = $ws->customerRegistration($documentId, $name, $email, $phone);

        $this->assertDatabaseCount(Customer::class, 1);
        $this->assertDatabaseHas(Customer::class, [
            'document_id' => $documentId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ]);

        $this->assertDatabaseCount(Wallet::class, 1);
        $this->assertDatabaseHas(Wallet::class, [
            'customer_id' => 1,
            'balance' => 0
        ]);

        $this->assertSame([
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Customer registered successfully',
            'data' => [
                'customer' => [
                    'document_id' => $documentId,
                    'name' => $name,
                    'email' => $email,
                    'phone' => $phone,
                    'updated_at' => '2024-01-01T00:00:00.000000Z',
                    'created_at' => '2024-01-01T00:00:00.000000Z',
                    'id' => 1
                ],
                'wallet' => [
                    'balance' => 0.0,
                    'customer_id' => 1,
                    'updated_at' => '2024-01-01T00:00:00.000000Z',
                    'created_at' => '2024-01-01T00:00:00.000000Z',
                    'id' => 1
                ]
            ]
        ], (array)$response);
    }

    public function test_register_customer_with_existing_email(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');

        $documentId = '123456789';
        $name = 'John Doe';
        $email = 'johndoe@example.com';
        $phone = '123456789';

        $this->createCustomer([
            'document_id' => $documentId,
            'name' => $name,
            'email' => $email,
            'phone' => $phone
        ]);

        $this->assertDatabaseCount(Customer::class, 1);

        $ws = new WebService();

        $response = $ws->customerRegistration($documentId, $name, $email, $phone);

        $this->assertDatabaseCount(Customer::class, 1);
        $this->assertDatabaseCount(Wallet::class, 0);

        $this->assertSame([
            'success' => false,
            'cod_error' => '422',
            'message_error' => 'The document id has already been taken. (and 2 more errors)',
            'data' => [
                'document_id' => [
                    'The document id has already been taken.'
                ],
                'email' => [
                    'The email has already been taken.'
                ],
                'phone' => [
                    'The phone has already been taken.'
                ]
            ]
        ], (array)$response);
    }

    public function test_wallet_recharge(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');

        $customerAttributes = [
            'document_id' => '123456789',
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '123456789'
        ];

        $customer = $this->createCustomer($customerAttributes);
        $wallet = $this->createWallet($customer, [
            'balance' => 0
        ]);

        $ws = new WebService();

        $documentId = $customerAttributes['document_id'];
        $phone = $customerAttributes['phone'];
        $amount = 10000;

        $this->assertDatabaseHas(Wallet::class, [
            'customer_id' => $customer->id,
            'balance' => 0
        ]);

        $this->assertDatabaseMissing(Wallet::class, [
            'customer_id' => $customer->id,
            'balance' => 10000
        ]);

        $response = $ws->recharge($documentId, $phone, $amount);

        $this->assertDatabaseHas(Wallet::class, [
            'customer_id' => $customer->id,
            'balance' => 10000
        ]);

        $this->assertSame([
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Recharge processed successfully',
            'data' => [
                'wallet' => [
                    'id' => $wallet->id,
                    'balance' => 10000.0,
                    'customer_id' => $customer->id,
                    'created_at' => '2024-01-01T00:00:00.000000Z',
                    'updated_at' => '2024-01-01T00:00:00.000000Z',
                ]
            ]
        ], (array)$response);
    }

    public function test_wallet_recharge_with_invalid_customer(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');

        $ws = new WebService();

        $documentId = '123456789';
        $phone = '123456789';
        $amount = 10000;

        $response = $ws->recharge($documentId, $phone, $amount);

        $this->assertDatabaseCount(Customer::class, 0);
        $this->assertDatabaseCount(Wallet::class, 0);

        $this->assertSame([
            'success' => false,
            'cod_error' => '422',
            'message_error' => 'The selected document id is invalid. (and 1 more error)',
            'data' => [
                'document_id' => [
                    'The selected document id is invalid.'
                ],
                'phone' => [
                    'The selected phone is invalid.'
                ]
            ]
        ], (array)$response);
    }

    public function test_wallet_payment__(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');

        Notification::fake();

        $customerAttributes = [
            'document_id' => '123456789',
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '123456789'
        ];

        $customer = $this->createCustomer($customerAttributes);
        $wallet = $this->createWallet($customer, [
            'balance' => 10000
        ]);

        Session::shouldReceive('getId')
            ->once()
            ->andReturn('mocked-session-id');

        $ws = new WebService();
        $response = $ws->payment($customerAttributes['document_id'], $customerAttributes['phone'], 5000);

        Notification::assertSentTo(
            $customer,
            PaymentConfirmation::class,
            function (PaymentConfirmation $notification, $channels) use ($customer, $wallet) {
                return $notification->transaction->wallet->customer->is($customer) &&
                    $notification->transaction->wallet->is($wallet);
            }
        );

        $this->assertSame([
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Payment processed successfully.',
            'data' => [
                'transaction' => [
                    'id' => 1,
                    'type' => TransactionTypeEnum::Debit->value,
                    'status' => TransactionStatusEnum::Pending->value,
                    'session_id' => 'mocked-session-id',
                    'amount' => 5000.0,
                    'expires_at' => '2024-01-01 00:10:00',
                    'confirmed_at' => null,
                    'wallet_id' => 1,
                    'created_at' => '2024-01-01T00:00:00.000000Z',
                    'updated_at' => '2024-01-01T00:00:00.000000Z',
                ]
            ]
        ], (array)$response);
    }

    public function test_wallet_payment_with_insufficient_funds(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');

        $customerAttributes = [
            'document_id' => '123456789',
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '123456789'
        ];

        $customer = $this->createCustomer($customerAttributes);

        $wallet = $this->createWallet($customer, [
            'balance' => 0
        ]);

        $ws = new WebService();

        $response = $ws->payment($customerAttributes['document_id'], $customerAttributes['phone'], 5000);

        $this->assertDatabaseCount(Transaction::class, 0);

        $this->assertSame([
            'success' => false,
            'cod_error' => '400',
            'message_error' => 'Wallet with insufficient funds',
            'data' => []
        ], (array)$response);
    }

    public function test_wallet_payment_confirmation(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');
        $customerAttributes = [
            'document_id' => '123456789',
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '123456789'
        ];

        $customer = $this->createCustomer($customerAttributes);

        $wallet = $this->createWallet($customer, [
            'balance' => 10000
        ]);

        $attributes = [
            'amount' => 5000,
            'session_id' => 'mocked-session-id',
            'token' => 123456,
            'type' => TransactionTypeEnum::Debit,
            'status' => TransactionStatusEnum::Pending,
            'expires_at' => now()->addMinutes(10),
            'confirmed_at' => null,
        ];

        $transaction = $this->createTransaction($wallet, $attributes);

        $ws = new WebService();

        $response = $ws->paymentConfirmation($attributes['session_id'], $attributes['token']);

        $this->assertDatabaseHas(Transaction::class, [
            'id' => $transaction->id,
            'status' => TransactionStatusEnum::Confirmed,
            'confirmed_at' => '2024-01-01 00:00:00',
            'token' => '000000'
        ]);

        $this->assertDatabaseHas(Wallet::class, [
            'id' => $transaction->wallet->id,
            'balance' => 5000
        ]);

        $this->assertSame([
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Payment confirmation processed successfully.',
            'data' => [
                'wallet' => [
                    'id' => 1,
                    'balance' => 5000.0,
                    'customer_id' => 1,
                    'created_at' => '2024-01-01T00:00:00.000000Z',
                    'updated_at' => '2024-01-01T00:00:00.000000Z',
                ],
                'transaction' => [
                    'id' => 1,
                    'type' => TransactionTypeEnum::Debit->value,
                    'status' => TransactionStatusEnum::Confirmed->value,
                    'session_id' => 'mocked-session-id',
                    'amount' => 5000.0,
                    'expires_at' => '2024-01-01 00:10:00',
                    'confirmed_at' => '2024-01-01 00:00:00',
                    'wallet_id' => 1,
                    'created_at' => '2024-01-01T00:00:00.000000Z',
                    'updated_at' => '2024-01-01T00:00:00.000000Z',
                ]
            ]
        ], (array)$response);
    }

    public function test_wallet_payment_confirmation_with_invalid_transaction(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');

        $ws = new WebService();

        $session_id = 'mocked-session-id';
        $token = 123456;

        $response = $ws->paymentConfirmation($session_id, $token);

        $this->assertDatabaseCount(Transaction::class, 0);

        $this->assertSame([
            'success' => false,
            'cod_error' => '422',
            'message_error' => 'The selected session id is invalid. (and 1 more error)',
            'data' => [
                'session_id' => [
                    'The selected session id is invalid.'
                ],
                'token' => [
                    'The selected token is invalid.'
                ]
            ]
        ], (array)$response);
    }

    public function test_wallet_balance_inquiry(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');

        $customerAttributes = [
            'document_id' => '123456789',
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'phone' => '123456789'
        ];

        $customer = $this->createCustomer($customerAttributes);
        $wallet = $this->createWallet($customer, [
            'balance' => 10000
        ]);

        $ws = new WebService();

        $response = $ws->balanceInquiry($customerAttributes['document_id'], $customerAttributes['phone']);

        $this->assertSame([
            'success' => true,
            'cod_error' => '00',
            'message_error' => 'Balance inquiry processed successfully.',
            'data' => [
                'wallet' => [
                    'id' => 1,
                    'balance' => 10000.0,
                    'customer_id' => 1,
                    'created_at' => '2024-01-01T00:00:00.000000Z',
                    'updated_at' => '2024-01-01T00:00:00.000000Z',
                ]
            ]
        ], (array)$response);
    }

    public function test_wallet_balance_inquiry_with_invalid_customer(): void
    {
        Carbon::setTestNow('2024-01-01 00:00:00');

        $ws = new WebService();

        $documentId = '123456789';
        $phone = '123456789';

        $response = $ws->balanceInquiry($documentId, $phone);

        $this->assertDatabaseCount(Customer::class, 0);
        $this->assertDatabaseCount(Wallet::class, 0);

        $this->assertSame([
            'success' => false,
            'cod_error' => '422',
            'message_error' => 'The selected document id is invalid. (and 1 more error)',
            'data' => [
                'document_id' => [
                    'The selected document id is invalid.'
                ],
                'phone' => [
                    'The selected phone is invalid.'
                ]
            ]
        ], (array)$response);
    }
}
