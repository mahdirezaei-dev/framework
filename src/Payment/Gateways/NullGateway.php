<?php

declare(strict_types=1);

/**
 * Contains the NullGateway class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Payment\Gateways;

use Illuminate\Http\Request;
use Vanilo\Contracts\Address;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Contracts\PaymentRequest;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\TransactionHandler;
use Vanilo\Payment\Requests\NullRequest;
use Vanilo\Payment\Responses\NullResponse;

class NullGateway implements PaymentGateway
{
    public static function getName(): string
    {
        return __('Offline');
    }

    public static function svgIcon(): string
    {
        return '<svg viewBox="0 0 512 512"><path fill="#A3A3A3" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm0-352a96 96 0 1 1 0 192 96 96 0 1 1 0-192z"/></svg>';
    }

    public function createPaymentRequest(
        Payment $payment,
        Address $shippingAddress = null,
        array $options = []
    ): PaymentRequest {
        return new NullRequest($payment);
    }

    public function processPaymentResponse(Request $request, array $options = []): PaymentResponse
    {
        return new NullResponse();
    }

    public function transactionHandler(): ?TransactionHandler
    {
        return null;
    }

    public function isOffline(): bool
    {
        return true;
    }
}
