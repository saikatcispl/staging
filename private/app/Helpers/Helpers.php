<?php

use Illuminate\Translation\Translator;

if (!function_exists('pr'))
{

    function pr($param = [], $continue = true, $label = NULL)
    {
        if (!empty($label))
        {
            echo '<p>-- ' . $label . ' --</p>';
        }

        echo '<pre>';
        print_r($param);
        echo '</pre><br />';

        if (!$continue)
        {
            die('-- code execution discontinued --');
        }
    }

}

if (!function_exists('siteurl'))
{

    function siteurl($url = NULL)
    {
        if (!empty($url))
        {
            $returnUrl = rtrim(url($url), '/') . '/';
        }
        else
        {
            $returnUrl = rtrim(url(), '/') . '/';
        }

        return str_replace(['http:', 'https:'], PROTOCOL, $returnUrl);
    }

}

if (!function_exists('hashurl'))
{

    function hashurl($url = NULL)
    {
        if (!empty($url))
        {
            return siteurl('dashboard') . '#' . trim($url, '/') . '/';
        }
        else
        {
            return '#';
        }
    }

}

if (!function_exists('currurl'))
{

    function currurl()
    {
        return rtrim(Request::url(), '/') . '/';
    }

}

if (!function_exists('minify'))
{

    function minify($assets = [], $type = 'css')
    {
        $type = !empty($type) ? strtolower($type) : 'html';

        if ($type == 'css')
        {
            header('Content-type: text/css');
        }
        elseif ($type == 'js')
        {
            header('Content-type: text/javascript');
        }
        else
        {
            header('Content-type: text/html');
        }

        if (empty($assets) || !is_array($assets))
        {
            return '';
        }

        ob_start("compress");

        foreach ($assets as $asset)
        {
            include($asset);
        }

        ob_end_flush();
    }

}

if (!function_exists('compress'))
{

    function compress($minify)
    {
        /* remove comments */
        $minify = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $minify);

        /* remove tabs, spaces, newlines, etc. */
        $minify = str_replace(["\r\n", "\r", "\n", "\t", '  ', '    ', '    '], '', $minify);

        return $minify;
    }

}

if (!function_exists('http_exception'))
{

    function http_exception($request = NULL, $status = 400)
    {
        $method = is_object($request) ? ($request->ajax() ? 'AJAX' : strtoupper($request->method())) : 'GET';

        if ($method == 'AJAX')
        {
            $trans = has_trans("httperros.$status") ? trans("httperros.$status") : trans('httperros.400');

            return response()->json(['success' => false, 'message' => $trans], $status);
        }
        else
        {
            $view = 'errors.' . $status;

            if (!view()->exists($view))
            {
                $view = 'errors.400';
            }

            return response()->view($view, [], $status);
        }
    }

}

if (!function_exists('has_trans'))
{

    function has_trans($key = NULL)
    {
        if (empty($key))
        {
            return false;
        }

        return trans($key) !== $key;
    }

}

if (!function_exists('setCsrfCookie'))
{

    function setCsrfCookie($token)
    {
        return setcookie('sbuilder_dev_login', $token, 0, '/');
    }

}

if (!function_exists('hasCsrfCookie'))
{

    function hasCsrfCookie()
    {
        return isset($_COOKIE['sbuilder_dev_login']) && !empty($_COOKIE['sbuilder_dev_login']);
    }

}

if (!function_exists('jsonOutput'))
{

    function jsonOutput($params = [], $echo = true, $header = false)
    {
        if (empty($params) || (!is_array($params) && !is_object($params)))
        {
            $output = json_encode([
                'success' => false,
                'message' => Lang::get('messages.DEFAULT.INVALID_ARGUMENTS'),
                'data' => ['input_data' => $params]]);

            if ($echo)
            {
                echo $output;
            }
            else
            {
                return $output;
            }
        }
        else
        {
            if ($echo)
            {
                if ($header)
                {
                    return response()->json($params);
                }
                else
                {
                    echo json_encode($params);
                }
            }
            else
            {
                return json_encode($params);
            }
        }
    }

}

if (!function_exists('scriptRedirect'))
{

    function scriptRedirect($fragment = NULL, $withHtml = true)
    {
        $output = '';

        if ($withHtml)
        {
            $output .= '<html><head><title>Redirecting...</title></head><body style="display:none;">';
        }

        $output .= '<script type="text/javascript">window.location = "' . siteurl('dashboard') . '#' . trim($fragment, '/') . '/";</script>';

        if ($withHtml)
        {
            $output .= '</body></html>';
        }

        return $output;
    }

}

if (!function_exists('clientTable'))
{

    function clientTable($table = NULL, $includePrefix = true, $client_id = NULL)
    {
        $tp = DB::getTablePrefix();

        if (empty($client_id) && Session::has('client_id'))
        {
            $clientId = Session::get('client_id');
        }

        if (!empty($client_id))
        {
            $clientId = $client_id;
        }
        return ($includePrefix ? $tp : '') . $clientId . '_' . $table;
    }

}

