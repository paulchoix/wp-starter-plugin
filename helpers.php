<?php
namespace Starter_Plugin\Helpers;


// Strips tags and returns excerpt based on length without breaking words
function excerpt( string $string, int $length = 260, string $suffix = '...' )
{
    $string = strip_tags( $string );
    if ( strlen( $string ) < $length ) return $string;
    $sub_string = substr( $string, 0, $length + strlen( $suffix ) );
    $sub_space = strrpos( $sub_string, ' ' );
    $string = substr( $string, 0, $sub_space );
    return $string . $suffix;
}


// Adapted from: https://stackoverflow.com/questions/2955251/php-function-to-make-slug-url-string
function slugify( string $text, string $divider = '-' )
{
  $text = preg_replace('~[^\pL\d]+~u', $divider, $text);
  $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
  $text = preg_replace('~[^-\w]+~', '', $text);
  $text = trim($text, $divider);
  $text = preg_replace('~-+~', $divider, $text);
  $text = strtolower($text);

  if( empty( $text ) ) return 'n-a';
  return $text;
}