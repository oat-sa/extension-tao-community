<?php
require 'vendor/autoload.php';

use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\MultiSearcher;
use ZendSearch\Lucene\Search\QueryParser;

$stem = function ( $e ) {
    return \Porter::Stem( $e );
};

$q = isset( $_GET['q'] ) ? $_GET['q'] : null;
$q = htmlentities( $q );
$q = implode( '+', array_map( $stem, explode( ' ', $q ) ) );


header( 'Content-Type: application/json' );
$output = array();

if ($q) {
    $indexer = Lucene::open( '../_index' );
    $search  = new MultiSearcher( array( $indexer ) );
    $query   = QueryParser::parse( $q );

    $result = $search->find( $query );

    foreach ($result as $hit) {
        $title = strtolower(str_replace( '-', ' ', $hit->name ));

        $resultUrl =  '../'.$hit->fileName;
        $output[]  = array(
            'href' => $resultUrl,
            'name' => ucfirst( $title ),
            'preview' => $query->htmlFragmentHighlightMatches(
                substr( preg_replace( "/\s+|$title/i", " ", $hit->body ), 0, 300 ) . '...'
            ),
        );
    }
}
echo json_encode( $output );
