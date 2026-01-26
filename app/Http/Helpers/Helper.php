<?php

//make slug

use App\Models\ProductVariation;
use App\Models\Setting;
use Illuminate\Routing\Route;

if (!function_exists('truncateString')) {
    function truncateString($string, $maxLength)
    {
        return strlen($string) > $maxLength ? mb_substr($string, 0, $maxLength, 'UTF-8') . '...' : $string;
    }
}


if (!function_exists('createSlug')) {
    function createSlug($string)
    {
        $slug = preg_replace('/\s+/u', '-', trim($string));
        $slug = str_replace('/', '', $slug);
        $slug = str_replace('?', '', $slug);
        $slug = str_replace(',', '', $slug);

        return mb_strtolower($slug);
    }
}

//for editor that replace and store url and image
if (!function_exists('replaceBaseUrl')) {
    function replaceBaseUrl($html, $type)
    {
        $startDelimiter = 'src=""';
        if ($type == 'summernote') {
            $endDelimiter = '/assets/img/summernote';
        } elseif ($type == 'pagebuilder') {
            $endDelimiter = '/assets/img';
        }

        $startDelimiterLength = strlen($startDelimiter);
        $endDelimiterLength = strlen($endDelimiter);
        $startFrom = $contentStart = $contentEnd = 0;

        while (false !== ($contentStart = strpos($html, $startDelimiter, $startFrom))) {
            $contentStart += $startDelimiterLength;
            $contentEnd = strpos($html, $endDelimiter, $contentStart);

            if (false === $contentEnd) {
                break;
            }

            $html = substr_replace($html, url('/'), $contentStart, $contentEnd - $contentStart);
            $startFrom = $contentEnd + $endDelimiterLength;
        }

        return $html;
    }
}

//price format with currency sybol
if (!function_exists('currency_symbol')) {
    function currency_symbol($value)
    {
        if (app('websiteSettings')->currency_symbol_position == 'left') {
            $data =  app('websiteSettings')->currency_symbol . number_format($value, 2);
            return str_replace(' ', '', $data);
        } else {
            $data =  number_format($value, 2) . app('websiteSettings')->base_currency_symbol;
            return str_replace(' ', '', $data);
        }
    }
}

//price format with currency sybol for ordered product
if (!function_exists('currency_symbol_order')) {
    function currency_symbol_order($value, $symbol, $position)
    {
        if ($position == 'left') {
            $data =  $symbol . number_format($value, 2);
            return str_replace(' ', '', $data);
        } else {
            $data =  number_format($value, 2) . $symbol;
            return str_replace(' ', '', $data);
        }
    }
}



//validation
if (!function_exists('customValid')) {
    function customValid($field, $errors)
    {
        $class = '';
        if ($errors && $errors->has($field)) {
            $class = 'is-invalid';
        } else if (old($field)) {
            $class = 'is-valid';
        }
        return $class;
    }
}


if (!function_exists('check_variation')) {
    function check_variation($productId)
    {
        $total = 0;
        $total =   ProductVariation::where('product_id', $productId)->count();
        return $total;
    }
}

if (!function_exists('generateOrderNumber')) {
    function generateOrderNumber($char)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $orderNumber = '';
        for ($i = 0; $i < $char; $i++) {
            $orderNumber .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $orderNumber;
    }
}


if (!function_exists('hexToRgba')) {

    function hexToRgba($hex, $alpha = .5)
    {
        // Remove the hash at the start if it's there
        $hex = ltrim($hex, '#');

        // Parse the hex color
        if (strlen($hex) == 6) {
            list($r, $g, $b) = sscanf($hex, "%02x%02x%02x");
        } elseif (strlen($hex) == 3) {
            list($r, $g, $b) = sscanf($hex, "%1x%1x%1x");
            $r = $r * 17;
            $g = $g * 17;
            $b = $b * 17;
        } else {
            return '10, 71, 46';
        }

        // Ensure alpha is between 0 and 1
        $alpha = min(max($alpha, 0), 1);

        // Return the rgba color code
        return "$r, $g, $b";
    }
}


/**
 * get routes based on menubuilder
 */
if (!function_exists('getHref')) {
    function getHref($route)
    {
        try {
            if ($route === 'Home' && Route::has('frontend.index')) {
                return route('frontend.index');
            } elseif ($route === 'Contact' && Route::has('frontend.contact')) {
                return route('frontend.contact');
            } elseif ($route === 'Pricing' && Route::has('frontend.pricing')) {
                return route('frontend.pricing');
            } elseif ($route === 'Blog' && Route::has('frontend.blog')) {
                return route('frontend.blog');
            } elseif ($route === 'About' && Route::has('frontend.about')) {
                return route('frontend.about');
            }
        } catch (\Exception $e) {
        }

        return '#';
    }
}

if (!function_exists('renderMenu')) {
    function renderMenu($items, $parentId = null)
    {
        foreach ($items as $key => $item) {
            $id = 'id_' . ($parentId ? $parentId . '_' . $key + 1 : $key + 1);
            echo '<li class="list-group-item menu-item rounded shadow-sm mb-2"
                  data-title="' . $item['title'] . '"
                  data-url="' . $item['url'] . '"
                  data-type="' . @$item['type'] . '"
                  data-target="' . $item['target'] . '"
                  id="' . $id . '">
                <div class="d-flex justify-content-between align-items-center">
                    <span class="item-label fw-semibold">' . $item['title'] . ' <small class="text-muted">(' . $item['target'] . ')</small></span>
                    <div>
                        <button class="btn btn-sm btn-outline-primary edit me-1" data-id="' . $id . '" title="Edit"><i class="fa fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger remove" title="Remove"><i class="fa fa-trash"></i></button>
                    </div>
                </div>
                <ul class="nested list-group mt-2 ps-3">';
            if (!empty($item['children'])) {
                renderMenu($item['children'], $id);
            }
            echo '</ul></li>';
        }
    }
}



if (!function_exists('getTableStatusBadgeClass')) {
    function getTableStatusBadgeClass($status)
    {
        $classes = [
            'available'   => 'bg-success',
            'occupied'    => 'bg-warning',
            'reserved'    => 'bg-danger',
            'cleaning'    => 'bg-info',
            'unavailable' => 'bg-secondary',
        ];

        return $classes[$status] ?? 'bg-secondary';
    }
}
