/**
 * External dependencies
 */
import $ from 'jquery';

/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';
import { render } from '@wordpress/element';
import Swal from 'sweetalert2';

const nonce = rtDevAlert.rest.nonce;
apiFetch.use( apiFetch.createNonceMiddleware( nonce ) );

import App from './App';

let rtDevSiteAlertContainer = document.getElementById( 'rt-dev-site-alert-container' );

if ( rtDevSiteAlertContainer ) {
	render( <App />, rtDevSiteAlertContainer );
}

const isSavingPost = () => {

	// State data necessary to establish if a save is occuring.
	const isSaving = wp.data.select( 'core/editor' ).isSavingPost() || wp.data.select( 'core/editor' ).isAutosavingPost();
	const isSaveable = wp.data.select( 'core/editor' ).isEditedPostSaveable();
	const isPostSavingLocked = wp.data.select( 'core/editor' ).isPostSavingLocked();
	const hasNonPostEntityChanges = wp.data.select( 'core/editor' ).hasNonPostEntityChanges();
	const isAutoSaving = wp.data.select( 'core/editor' ).isAutosavingPost();
	const isButtonDisabled = isSaving || ! isSaveable || isPostSavingLocked;

	// Reduces state into checking whether the post is saving and that the save button is disabled.
	const isBusy = ! isAutoSaving && isSaving;
	const isNotInteractable = isButtonDisabled && ! hasNonPostEntityChanges;

	return isBusy && isNotInteractable;
};

$( document ).ready( function () {

	if ( null === wp.data.select( 'core/editor' ) ) {
		return;
	}

	let wasSaving = isSavingPost();

	wp.data.subscribe( function () {

		// New saving state
		let isSaving = isSavingPost();

		// It is done saving if it was saving and it no longer is.
		let isDoneSaving = wasSaving && ! isSaving;

		// Update value for next use.
		wasSaving = isSaving;

		let getCurrentPostId = wp.data.select( 'core/editor' ).getCurrentPostId();

		if ( isDoneSaving ) {
			apiFetch( {
				path: '/wp-json/rt-dev-site-alert/v1/post/validate?post_id=' + getCurrentPostId
			} )
				.then( ( resp ) => {
				} )
				.catch( ( err ) => {

					if ( 'rtdsa_found_url' !== err.code ) {
						return;
					}

					Swal.fire( {
						title: 'Warning!',
						text: err.message,
						icon: 'warning',
						showConfirmButton: false
					} );
				} );
		}
	} );
} );
