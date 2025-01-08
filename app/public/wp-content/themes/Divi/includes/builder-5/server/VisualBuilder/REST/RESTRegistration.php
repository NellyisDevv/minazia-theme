<?php
/**
 * REST: RESTRegistration class.
 *
 * @package Divi
 * @since ??
 */

namespace ET\Builder\VisualBuilder\REST;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

use ET\Builder\Framework\DependencyManagement\Interfaces\DependencyInterface;
use ET\Builder\Framework\Route\RESTRoute;
use ET\Builder\Packages\GlobalData\GlobalDataController;
use ET\Builder\Packages\GlobalData\GlobalPresetController;
use ET\Builder\Packages\Module\Layout\Components\DynamicContent\DynamicContentOptionsController;
use ET\Builder\Packages\Module\Layout\Components\DynamicData\DynamicDataController;
use ET\Builder\Packages\Module\Options\Conditions\RESTControllers\AuthorConditionRESTController;
use ET\Builder\Packages\Module\Options\Conditions\RESTControllers\ConditionsStatusRESTController;
use ET\Builder\Packages\Module\Options\Conditions\RESTControllers\PostMetaFieldsRESTController;
use ET\Builder\Packages\Module\Options\Conditions\RESTControllers\PostsRESTController;
use ET\Builder\Packages\Module\Options\Conditions\RESTControllers\CategoriesRESTController;
use ET\Builder\Packages\Module\Options\Conditions\RESTControllers\TagsRESTController;
use ET\Builder\Packages\Module\Options\Conditions\RESTControllers\UserRoleConditionRESTController;
use ET\Builder\Packages\Module\Options\Conditions\RESTControllers\PostTypeConditionRESTController;
use ET\Builder\Packages\ModuleLibrary\Audio\AudioController;
use ET\Builder\Packages\ModuleLibrary\Blog\BlogController;
use ET\Builder\Packages\ModuleLibrary\Blog\PostTypeController;
use ET\Builder\Packages\ModuleLibrary\FilterablePortfolio\FilterablePortfolioController;
use ET\Builder\Packages\ModuleLibrary\FullwidthPortfolio\FullwidthPortfolioController;
use ET\Builder\Packages\ModuleLibrary\FullwidthMenu\FullwidthMenuHTMLController;
use ET\Builder\Packages\ModuleLibrary\FullwidthMenu\FullwidthMenuTermsController;
use ET\Builder\Packages\ModuleLibrary\Gallery\GalleryController;
use ET\Builder\Packages\ModuleLibrary\Menu\MenuHTMLController;
use ET\Builder\Packages\ModuleLibrary\Menu\MenuTermsController;
use ET\Builder\Packages\ModuleLibrary\Portfolio\PortfolioController;
use ET\Builder\Packages\ModuleLibrary\PostNavigation\PostNavigationController;
use ET\Builder\Packages\ModuleLibrary\Sidebar\SidebarController;
use ET\Builder\Packages\ModuleLibrary\Video\VideoCoverController;
use ET\Builder\Packages\ModuleLibrary\Video\VideoHTMLController;
use ET\Builder\Packages\ModuleLibrary\Video\VideoThumbnailController;
use ET\Builder\Packages\ModuleLibrary\VideoSlider\VideoSlideThumbnailController;
use ET\Builder\Packages\ShortcodeModule\Module\ShortcodeModuleBatchController;
use ET\Builder\Packages\ShortcodeModule\Module\ShortcodeModuleController;
use ET\Builder\VisualBuilder\REST\CloudApp\CloudAppController;
use ET\Builder\VisualBuilder\REST\CustomFont\CustomFontController;
use ET\Builder\VisualBuilder\REST\DiviLibrary\DiviLibraryController;
use ET\Builder\VisualBuilder\REST\Portability\PortabilityController;
use ET\Builder\VisualBuilder\REST\SyncToServer\SyncToServerController;
use ET\Builder\VisualBuilder\REST\UpdateDefaultColors\UpdateDefaultColorsController;
use ET\Builder\VisualBuilder\REST\SpamProtectionService\SpamProtectionServiceController;
use ET\Builder\VisualBuilder\REST\EmailService\EmailServiceController;
use ET\Builder\VisualBuilder\SettingsData\SettingsDataController;


/**
 * `RESTRegistration` class registers REST API endpoints upon calling `load()`, These endpoints are used in different parts of Divi.
 *
 * This is a dependency class and can be used as a dependency for `DependencyTree`.
 *
 * @since ??
 */
class RESTRegistration implements DependencyInterface {

	/**
	 * Loads and registers all REST routes.
	 *
	 * @since ??
	 *
	 * @return void
	 */
	public function load(): void {

		$route = new RESTRoute( 'divi/v1' );

		/**
		 * `/settings-data` REST routes for getting `divi/settings`' state.
		 */
		$route
			->prefix( '/settings-data' )
			->group(
				function ( $router ) {
					$router->get( '/after-app-load', SettingsDataController::class );
				}
			);

		/**
		 * `/module-data` REST routes.
		 */
		$route
			->prefix( '/module-data' )
			->group(
				function ( $router ) {

					/**
					 * Gallery Module
					 */
					$router->get( '/gallery/attachments', GalleryController::class );

					/**
					 * Video module.
					 */
					$router->get( '/video/html', VideoHTMLController::class );
					$router->get( '/video/thumbnail', VideoThumbnailController::class );
					$router->get( '/video/cover', VideoCoverController::class );

					/**
					 * Video Slider module.
					 */
					$router->get( '/video-slide/thumbnail', VideoSlideThumbnailController::class );

					/**
					 * Audio module.
					 */
					$router->get( '/audio/html', AudioController::class );

					/**
					 * Menu module.
					 */
					$router->get( '/menu/html', MenuHTMLController::class );
					$router->get( '/menu/terms', MenuTermsController::class );

					/**
					 * Fullwidth Menu module.
					 */
					$router->get( '/fullwidth-menu/html', FullwidthMenuHTMLController::class );
					$router->get( '/fullwidth-menu/terms', FullwidthMenuTermsController::class );

					/**
					 * Portfolio module.
					 */
					$router->get( '/portfolio/posts', PortfolioController::class );

					/**
					 * Filterable Portfolio module.
					 */
					$router->get( '/filterable-portfolio/posts', FilterablePortfolioController::class );

					/**
					 * Post Navigation module.
					 */
					$router->get( '/post-navigation/navigation', PostNavigationController::class );

					/**
					 * Fullwidth Portfolio module.
					 */
					$router->get( '/fullwidth-portfolio/posts', FullwidthPortfolioController::class );

					/**
					 * Shortcode module.
					 *
					 * TODO feat(D5, Shortcode Module): Move Shortcode module REST API route registration
					 * to ShortcodeModule package.
					 *
					 * @see https://github.com/elegantthemes/Divi/issues/32183
					 */
					$router->get( '/shortcode-module/html', ShortcodeModuleController::class );
					$router->post(
						'/shortcode-module/html/batch',
						[
							'args'                => [ ShortcodeModuleBatchController::class, 'index_args' ],
							'callback'            => [ ShortcodeModuleBatchController::class, 'index' ],
							'permission_callback' => [ ShortcodeModuleBatchController::class, 'index_permission' ],
						]
					);

					/**
					 * Sidebar module.
					 */
					$router->get( '/sidebar/html', SidebarController::class );

					/**
					 * Blog module.
					 */
					$router->get( '/blog/posts', BlogController::class );

					/**
					 * Blog module.
					 */
					$router->get( '/blog/types', PostTypeController::class );
				}
			);

		/**
		 * `/dynamic-content` REST routes.
		 */
		$route
			->prefix( '/dynamic-content' )
			->group(
				function ( $router ) {

					/**
					 * Dynamic Content Options.
					 */
					$router->get( '/options', DynamicContentOptionsController::class );
				}
			);

		/**
		 * `/option-data` REST routes.
		 */
		$route
		->prefix( '/option-data' )
		->group(
			function ( $router ) {

				/**
				 * Conditions option.
				 */
				$router->post( '/conditions/status', ConditionsStatusRESTController::class );
				$router->get( '/conditions/posts', PostsRESTController::class );
				$router->get( '/conditions/post-meta-fields', PostMetaFieldsRESTController::class );
				$router->get( '/conditions/user-role', UserRoleConditionRESTController::class );
				$router->get( '/conditions/author', AuthorConditionRESTController::class );
				$router->get( '/conditions/post-type', PostTypeConditionRESTController::class );
				$router->get( '/conditions/categories', CategoriesRESTController::class );
				$router->get( '/conditions/tags', TagsRESTController::class );

			}
		);

		/**
		 * `/divi-library` REST routes.
		 */
		$route->post(
			'/divi-library',
			[
				'args'                => [ DiviLibraryController::class, 'index_args' ],
				'callback'            => [ DiviLibraryController::class, 'index' ],
				'permission_callback' => [ DiviLibraryController::class, 'index_permission' ],
			]
		);

		$route->post(
			'/divi-library/cloud-token',
			[
				'args'                => [ DiviLibraryController::class, 'get_token_args' ],
				'callback'            => [ DiviLibraryController::class, 'get_token' ],
				'permission_callback' => [ DiviLibraryController::class, 'get_token_permission' ],
			]
		);

		$route->post(
			'/divi-library/item',
			[
				'args'                => [ DiviLibraryController::class, 'show_args' ],
				'callback'            => [ DiviLibraryController::class, 'show' ],
				'permission_callback' => [ DiviLibraryController::class, 'show_permission' ],
			]
		);

		$route->post(
			'/divi-library/update-terms',
			[
				'args'                => [ DiviLibraryController::class, 'update_args' ],
				'callback'            => [ DiviLibraryController::class, 'update' ],
				'permission_callback' => [ DiviLibraryController::class, 'update_permission' ],
			]
		);

		$route->post(
			'/divi-library/update-item',
			[
				'args'                => [ DiviLibraryController::class, 'update_item_args' ],
				'callback'            => [ DiviLibraryController::class, 'update_item' ],
				'permission_callback' => [ DiviLibraryController::class, 'update_item_permission' ],
			]
		);

		$route->post(
			'/divi-library/convert-item',
			[
				'args'                => [ DiviLibraryController::class, 'convert_item_args' ],
				'callback'            => [ DiviLibraryController::class, 'convert_item' ],
				'permission_callback' => [ DiviLibraryController::class, 'convert_item_permission' ],
			]
		);

		$route->post(
			'/divi-library/split-item',
			[
				'args'                => [ DiviLibraryController::class, 'split_item_args' ],
				'callback'            => [ DiviLibraryController::class, 'split_item' ],
				'permission_callback' => [ DiviLibraryController::class, 'split_item_permission' ],
			]
		);

		$route->post(
			'/divi-library/load',
			[
				'args'                => [ DiviLibraryController::class, 'load_args' ],
				'callback'            => [ DiviLibraryController::class, 'load' ],
				'permission_callback' => [ DiviLibraryController::class, 'load_permission' ],
			]
		);
		$route->post(
			'/divi-library/create-item',
			[
				'args'                => [ DiviLibraryController::class, 'create_item_args' ],
				'callback'            => [ DiviLibraryController::class, 'create_item' ],
				'permission_callback' => [ DiviLibraryController::class, 'create_item_permission' ],
			]
		);
		$route->post(
			'/divi-library/save',
			[
				'args'                => [ DiviLibraryController::class, 'save_args' ],
				'callback'            => [ DiviLibraryController::class, 'save' ],
				'permission_callback' => [ DiviLibraryController::class, 'save_permission' ],
			]
		);
		$route->post(
			'/divi-library/upload-image',
			[
				'args'                => [ DiviLibraryController::class, 'upload_image_args' ],
				'callback'            => [ DiviLibraryController::class, 'upload_image' ],
				'permission_callback' => [ DiviLibraryController::class, 'upload_image_permission' ],
			]
		);
		$route->post(
			'/divi-library/item-location',
			[
				'args'                => [ DiviLibraryController::class, 'item_location_args' ],
				'callback'            => [ DiviLibraryController::class, 'item_location' ],
				'permission_callback' => [ DiviLibraryController::class, 'item_location_permission' ],
			]
		);

		/**
		 * `/custom-font` REST routes.
		 */
		$route->post(
			'/custom-font/add',
			[
				'args'                => [ CustomFontController::class, 'store_args' ],
				'callback'            => [ CustomFontController::class, 'store' ],
				'permission_callback' => [ CustomFontController::class, 'store_permission' ],
			]
		);
		$route->post(
			'/custom-font/remove',
			[
				'args'                => [ CustomFontController::class, 'destroy_args' ],
				'callback'            => [ CustomFontController::class, 'destroy' ],
				'permission_callback' => [ CustomFontController::class, 'destroy_permission' ],
			]
		);

		/**
		 * `/portability` REST routes.
		 */
		$route->post(
			'/portability/export',
			[
				'args'                => [ PortabilityController::class, 'show_args' ],
				'callback'            => [ PortabilityController::class, 'show' ],
				'permission_callback' => [ PortabilityController::class, 'show_permission' ],
			]
		);

		$route->post(
			'/portability/import',
			[
				'args'                => [ PortabilityController::class, 'store_args' ],
				'callback'            => [ PortabilityController::class, 'store' ],
				'permission_callback' => [ PortabilityController::class, 'store_permission' ],
			]
		);

		/**
		 * `/sync-to-server` REST routes.
		 */
		$route->post(
			'/sync-to-server',
			[
				'args'                => [ SyncToServerController::class, 'update_args' ],
				'callback'            => [ SyncToServerController::class, 'update' ],
				'permission_callback' => [ SyncToServerController::class, 'update_permission' ],
			]
		);

		/**
		 * `/update-default-colors` REST routes.
		 */
		$route->post(
			'/update-default-colors',
			[
				'args'                => [ UpdateDefaultColorsController::class, 'update_args' ],
				'callback'            => [ UpdateDefaultColorsController::class, 'update' ],
				'permission_callback' => [ UpdateDefaultColorsController::class, 'update_permission' ],
			]
		);

		/**
		 * `/dynamic-data` REST routes.
		 */
		$route->post(
			'/dynamic-data',
			[
				'args'                => [ DynamicDataController::class, 'index_args' ],
				'callback'            => [ DynamicDataController::class, 'index' ],
				'permission_callback' => [ DynamicDataController::class, 'index_permission' ],
			]
		);

		/**
		 * `/update-account` REST routes.
		 */
		$route->post(
			'/update-account',
			[
				'args'                => [ CloudAppController::class, 'update_account_args' ],
				'callback'            => [ CloudAppController::class, 'update_account' ],
				'permission_callback' => [ CloudAppController::class, 'update_account_permission' ],
			]
		);

		/**
		 * `/spam-protection-provider` REST routes.
		 */
		$route
			->prefix( '/spam-protection-service' )
			->group(
				function ( $router ) {
					$router->post(
						'/create',
						[
							'args'                => [ SpamProtectionServiceController::class, 'create_args' ],
							'callback'            => [ SpamProtectionServiceController::class, 'create' ],
							'permission_callback' => [ SpamProtectionServiceController::class, 'create_permission' ],
						]
					);
					$router->post(
						'/delete',
						[
							'args'                => [ SpamProtectionServiceController::class, 'delete_args' ],
							'callback'            => [ SpamProtectionServiceController::class, 'delete' ],
							'permission_callback' => [ SpamProtectionServiceController::class, 'delete_permission' ],
						]
					);
				}
			);

		/**
		 * `/email-service` REST routes.
		 */
		$route
			->prefix( '/email-service' )
			->group(
				function ( $router ) {
					$router->post(
						'/create',
						[
							'args'                => [ EmailServiceController::class, 'create_args' ],
							'callback'            => [ EmailServiceController::class, 'create' ],
							'permission_callback' => [ EmailServiceController::class, 'create_permission' ],
						]
					);
					$router->post(
						'/read',
						[
							'args'                => [ EmailServiceController::class, 'read_args' ],
							'callback'            => [ EmailServiceController::class, 'read' ],
							'permission_callback' => [ EmailServiceController::class, 'read_permission' ],
						]
					);
					$router->post(
						'/delete',
						[
							'args'                => [ EmailServiceController::class, 'delete_args' ],
							'callback'            => [ EmailServiceController::class, 'delete' ],
							'permission_callback' => [ EmailServiceController::class, 'delete_permission' ],
						]
					);
				}
			);

		/**
		 * `/spam-protection-service` REST routes.
		 */
		$route
		->prefix( '/spam-protection-service' )
		->group(
			function ( $router ) {
				$router->post(
					'/create',
					[
						'args'                => [ SpamProtectionServiceController::class, 'create_args' ],
						'callback'            => [ SpamProtectionServiceController::class, 'create' ],
						'permission_callback' => [ SpamProtectionServiceController::class, 'create_permission' ],
					]
				);
				$router->post(
					'/delete',
					[
						'args'                => [ SpamProtectionServiceController::class, 'delete_args' ],
						'callback'            => [ SpamProtectionServiceController::class, 'delete' ],
						'permission_callback' => [ SpamProtectionServiceController::class, 'delete_permission' ],
					]
				);
			}
		);

		/**
		 * `/global-data/global-colors` REST routes.
		 */
		$route->post(
			'/global-data/global-colors',
			[
				'args'                => [ GlobalDataController::class, 'update_global_colors_args' ],
				'callback'            => [ GlobalDataController::class, 'update_global_colors' ],
				'permission_callback' => [ GlobalDataController::class, 'update_global_colors_permission' ],
			]
		);

		/**
		 * `/global-data/global-colors` REST routes.
		 */
		$route->post(
			'/global-data/global-fonts',
			[
				'args'                => [ GlobalDataController::class, 'update_global_fonts_args' ],
				'callback'            => [ GlobalDataController::class, 'update_global_fonts' ],
				'permission_callback' => [ GlobalDataController::class, 'update_global_fonts_permission' ],
			]
		);

		/**
		 * Register route `/global-data/global-preset/sync`.
		 */
		$route->post(
			'/global-data/global-preset/sync',
			[
				'args'                => [ GlobalPresetController::class, 'sync_args' ],
				'callback'            => [ GlobalPresetController::class, 'sync' ],
				'permission_callback' => [ GlobalPresetController::class, 'sync_permission' ],
			]
		);

	}

}
