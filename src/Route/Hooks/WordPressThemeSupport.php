<?php

namespace Themosis\Route\Hooks;

use App\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Themosis\Core\Application;
use Themosis\Hook\Hookable;
use Themosis\Support\Facades\Action;
use Themosis\Support\Facades\Filter;

class WordPressThemeSupport extends Hookable
{
    /**
     * @var Request
     */
    protected $request;
    /**
     * @var Response
     */
    protected $response;
    /**
     * @var Kernel
     */
    protected $kernel;

    public function register()
    {
        Action::add('template_redirect', [$this, 'template_redirect'], PHP_INT_MAX);
        Filter::add('template_include', [$this, 'template_include'], PHP_INT_MAX);
    }


    protected function response_send()
    {
        $this->response->send();
        $this->kernel->terminate($request, $this->response);
        exit;
    }

    public function template_redirect()
    {
        $app = Application::getInstance();
        $this->kernel = $app->make(Kernel::class);

        $this->request = $app['request'];
        $this->response = $this->kernel->handle($this->request);


        if (404 != $this->response->getStatusCode() && ! empty($this->request->route()) && 'is_404' != $this->request->route()->getCondition()) {
            $this->response_send();
        }
    }

    public function template_include($template)
    {
        if (Str::endsWith($template, '404.php') && ! empty($this->request->route())) {
            $this->response_send();
        }
        error_reporting(0);

        return $template;
    }
}
