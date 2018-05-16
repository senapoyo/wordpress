<?php
/**
 * タクソノミーの全タームリストを返す(親子構造を維持したツリー構造)
 * Get custom taxonomy's all terms hierarchical list
 * 
 * @version  1.0
 * @author   senapoyo
 * @param    string    taxonomy
 * @return   array     Term Object Array
 */

// var_dump( get_all_terms( 'category' ) );

function get_all_terms( $taxonomy ) {
	$parents = get_terms( $taxonomy, array( 'parent' => 0 ) );
	return _get_all_terms( $taxonomy, $parents );
}

function _get_all_terms( $taxonomy, $parentsArray ) {
	foreach ( $parentsArray as $key => $parent ) {
		$terms = get_terms( $taxonomy, array( 'parent' => $parent->term_id ) );
		$parentsArray[$key]->children = _get_all_terms( $taxonomy, $terms );
	}
	return $parentsArray;
}
