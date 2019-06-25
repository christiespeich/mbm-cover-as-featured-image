<?php


function mbdbcafi_mbdb_installed() {
	return defined( 'MBDB_PLUGIN_VERSION' );
}

function mbdbcafi_get_all_books() {
	if ( ! mbdbcafi_mbdb_installed() ) {
		return;
	}
	// update thumb ids for all current books
	$books = get_posts( array(
		'post_type'      => 'mbdb_book',
		'posts_per_page' => - 1,
	) );

	return $books;
}

function mbdbcafi_set_attach_id( $book_id, $cover_id ) {
	set_post_thumbnail( $book_id, $cover_id );
}

function mbdbcafi_set_all_attach_ids() {
	$books = mbdbcafi_get_all_books();

	foreach ( $books as $book ) {
		$book_obj = MBDB()->book_factory->create_book( $book->ID );
		if ( $book_obj->cover_id != null && $book_obj->cover_id != '' ) {
			mbdbcafi_set_attach_id( $book->ID, $book_obj->cover_id );
		}
	}
}

function mbdbcafi_remove_attach_id( $book_id ) {
	delete_post_thumbnail( $book_id );
}

function mbdbcafi_remove_all_attach_ids() {
	$books = mbdbcafi_get_all_books();

	foreach ( $books as $book ) {
		mbdbcafi_remove_attach_id( $book->ID );
	}

}

