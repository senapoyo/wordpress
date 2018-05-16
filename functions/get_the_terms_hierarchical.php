<?php
/**
 * 記事の特定のタクソノミーに属するターム一覧を返す(親子構造を維持したツリー構造)
 * Retrieve the terms of the taxonomy that are attached to the post. (return Hierarchical List)
 * 
 * @version  1.0
 * @author   senapoyo
 * @param    integer   post_id
 * @param    string    taxonomy
 * @return   array     Term Object Array
 */

// var_dump( get_the_terms_hierarchical( get_the_ID(), 'category' ) );

function get_the_terms_hierarchical( $id, $taxonomy ) {
	$depthAry = $keyList = $parents = array();
	$terms = get_the_terms( $id, $taxonomy );
	if ( !empty( $terms ) ) {
		foreach ( $terms as $term ) {
			$keyList[$term->term_id] = $term;
		}
		foreach ( $keyList as $k => $term ) {
			if ( isset( $keyList[$term->parent] ) ) {
				if ( ! isset( $keyList[$term->parent]->children_id)) $keyList[$term->parent]->children_id = array();
				$keyList[$term->parent]->children_id[] = $k;
			}
		}
		foreach ( $keyList as $k => $term ) {
			if ( $term->parent === 0 || ! isset( $keyList[$term->parent] ) ) $parents[$k] = $term;
		}
		_get_the_terms_hierarchical( $keyList, $parents, $depthAry, 1 );
		$depthAry = $depthAry[1];
	}

	return $depthAry;
}

function _get_the_terms_hierarchical( $keyList, $parents, &$depthAry, $depth ) {
	foreach ( $parents as $k => $term ) {
		$depthAry[$depth][$k] = $term;
		if ( isset( $term->children_id ) ) {
			foreach ( $term->children_id as $child ) {
				if ( ! isset( $term->children ) ) $term->children = array();
				$term->children[$child] = $keyList[$child];
			}
			unset( $term->children_id );
			_get_the_terms_hierarchical( $keyList, $term->children, $depthAry, ( $depth + 1 ) );
		}
	}
}
