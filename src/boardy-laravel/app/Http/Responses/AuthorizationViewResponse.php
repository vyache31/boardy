<?php

namespace App\Http\Responses;

use Laravel\Passport\Contracts\AuthorizationViewResponse as Contract;
use Illuminate\Contracts\View\Factory as ViewFactory;

class AuthorizationViewResponse implements Contract
{
    protected $view;
    protected $parameters = [];

    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }

    public function withParameters(array $parameters = []): static
    {
        $clone = clone $this;
        $clone->parameters = $parameters;
        return $clone;
    }

    public function toResponse($request)
    {
        return $this->view->make('vendor.passport.authorize', $this->parameters);
    }
}
