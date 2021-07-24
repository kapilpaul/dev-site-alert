import { __ } from '@wordpress/i18n';
import { Button, TextControl, Notice } from '@wordpress/components';
import { useState, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';
import './style.scss';

function App() {
	const [ isSubmitted, setIsSubmitted ] = useState( false );
	const [ devSiteUrl, setDevSiteUrl ] = useState( '' );
	const [ notice, setNotice ] = useState( '' );
	const [ noticeType, setNoticeType ] = useState( '' );

	useEffect( () => {
		apiFetch( {
			path: '/wp-json/rt-dev-site-alert/v1/settings'
		} )
			.then( ( resp ) => {
				setDevSiteUrl( resp.dev_site_url );

			} )
			.catch( ( err ) => { } );

	}, [] );

	/**
	 * Save Settings
	 */
	const saveSettings = () => {
		setIsSubmitted( true );

		let data = {
			'dev_site_url': devSiteUrl
		};

		apiFetch( {
			path: '/wp-json/rt-dev-site-alert/v1/settings',
			method: 'POST',
			data: data
		} )
			.then( ( resp ) => {
				setIsSubmitted( false );
				setNoticeType( 'success' );
				setNotice( __( 'Settings Saved!', 'rt-dev-site-alert' ) );

			} )
			.catch( ( err ) => {
				setIsSubmitted( false );
				setNoticeType( 'error' );
				setNotice( __( 'Could not save the settings!', 'rt-dev-site-alert' ) );
			} );
	};

	const renderNotice = () => {
		if ( '' === notice ) {
			return;
		}

		return <Notice status={ noticeType } onRemove={ () => setNotice( '' ) }>{ notice }</Notice>;
	};

	return (
		<>
			<div className="wrap">
				<h2>{ __( 'Dev Site Alert', 'rt-dev-site-alert' ) }</h2>

				{ renderNotice() }

				<div className="settings_container">

					<div className="group">
						<TextControl
							label="Dev Site URL"
							className={ 'dev-site-url-input' }
							value={ devSiteUrl }
							onChange={ ( value ) => setDevSiteUrl( value ) }
						/>
					</div>

					<Button
						type="submit"
						isPrimary={ true }
						onClick={ () => saveSettings() }
						isBusy={ isSubmitted }
						disabled={ isSubmitted }
					>
						{ isSubmitted ? __( 'Saving', 'rt-dev-site-alert' ) : __( 'Save', 'rt-dev-site-alert' ) }
					</Button>
				</div>
			</div>
		</>
	);
}

export default ( App );
