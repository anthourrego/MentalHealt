<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use Config\Database;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    public $content;
    protected $db;
    protected $routes;
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->routes = service("routes");
        $this->db = Database::connect();

        $this->content['Project_Name'] = "Mental Health";
        $this->LJQuery();
        $this->LAlertify();
        $this->LFontAwesome();

        if(session()->has("logged_in") && session()->get("logged_in")){
            $this->LAdminLTE();
            $this->LOverlayScrollbars();
            $this->LGlobal();
            $this->content['Profile'] = session()->get("profile");
            $theme = (object) [
                "sidebar" => "primary",
                "bg_logo" => "",
                "border_loader" => "",
            ];
            if ($this->content['Profile'] == 2) {
                $theme->sidebar = "lightblue";
                $theme->bg_logo = "bg-lightblue";
                $theme->border_loader = "border-lightblue";
            } else if ($this->content['Profile'] == 3) {
                $theme->sidebar = "teal";
                $theme->bg_logo = "bg-teal";
                $theme->border_loader = "border-teal";

            }
            $this->content['theme'] = $theme;
        } else {
            $this->LBootstrap();
        }

    }

    //Librerias personalizadas en assets/Libraries
    public function LJQueryValidation()
    {
        $this->content['js_lib'][] = [
            'jquery-validation/jquery.validate.min.js',
            'jquery-validation/additional-methods.min.js',
            'jquery-validation/messages_es.min.js',
        ];
        
        $this->content['js_add'][] = [
            'validate.js'
        ];
    }

    public function LAlertify()
    {
        $this->content['css_lib'][] = [
            'alertifyjs/css/alertify.min.css',
            'alertifyjs/css/themes/bootstrap.min.css'
        ];

        $this->content['js_lib'][] = [
            'alertifyjs/alertify.min.js'
        ];
    }

    public function LOverlayScrollbars()
    {
        $this->content['css_lib'][] = [
            'OverlayScrollbars/css/OverlayScrollbars.min.css'
        ];

        $this->content['js_lib'][] = [
            'OverlayScrollbars/js/OverlayScrollbars.min.js'
        ];
    }

    public function LLightbox(){
        $this->content['css_lib'][] = [
            'lightbox/lightbox.min.css'
        ];

        $this->content['js_lib'][] = [
            'lightbox/lightbox.min.js'
        ];
    }

    public function LFancybox(){
        $this->content['css_lib'][] = [
            'fancybox/jquery.fancybox.min.css'
        ];

        $this->content['js_lib'][] = [
            'fancybox/jquery.fancybox.min.js'
        ];
    }

    public function LFullCalendar()
    {
        $this->content['js_lib'][] = [
            'fullCalendar/packages/core/index.global.min.js',
            'fullCalendar/packages/interaction/index.global.min.js',
            'fullCalendar/packages/daygrid/index.global.min.js',
            'fullCalendar/packages/timegrid/index.global.min.js',
            'fullCalendar/packages/list/index.global.min.js'
        ];
    }


    //Librerias personalizadas en el vendor que estan en el controaldor de Libraries
    public function LJQuery()
    {
        $this->content['js'][] = [
          'jquery/jquery.min.js'
        ];
    }

    public function LBootstrap()
    {
        $this->content['css'][] = [
            'bootstrap/bootstrap.min.css'
        ];

        $this->content['js'][] = [
            'bootstrap/bootstrap.min.js'
        ];
    }

    public function LFontAwesome()
    {
        $this->content['css'][] = [
            'fontawesome/all.min.css'
        ];
    }

    public function LAdminLTE(){
        $this->content['css'][] = [
            'adminLTE/adminlte.min.css'
        ];

        $this->content['js'][] = [
            'bootstrap/bootstrap.bundle.min.js'
            ,'adminLTE/adminlte.min.js'
        ];
    }

    public function LDataTables(){
        $this->content['css'][] = [
            'dataTables-bs4/dataTables.bootstrap4.min.css'
            ,'dataTables-buttons-bs4/buttons.bootstrap4.min.css'
        ];

        $this->content['js'][] = [
            'dataTables/dataTables.min.js'
            ,'dataTables-bs4/dataTables.bootstrap4.min.js'
            ,'dataTables-buttons/dataTables.buttons.min.js'
            ,'dataTables-buttons-bs4/buttons.bootstrap4.min.js'
            ,'jszip/jszip.min.js'
            ,'pdfmake/pdfmake.min.js'
            ,'pdfmake/vfs_fonts.js'
            ,'dataTables-buttons/buttons.html5.min.js'
            ,'dataTables-buttons/buttons.print.min.js'
            ,'dataTables-scroller/dataTables.scroller.min.js'
            ,'dataTables-select/dataTables.select.min.js'
        ];

        $this->content['js_add'][] = [
            'DataTables.js'
        ];
    }

    public function LMoment(){
        $this->content['js'][] = [
            'moment/moment.js',
            'moment-locale/es-mx.js'
        ];
    }

    public function LTempusDominus(){
        $this->content['css'][] = [
            'tempusDominus/tempusdominus-bootstrap-4.min.css'
        ];

        $this->content['js'][] = [
            'tempusDominus/tempusdominus-bootstrap-4.min.js'
        ];
    }

    public function LBootstrapSwitch(){
        $this->content['js'][] = [
            'bootstrapSwitch/bootstrap-switch.min.js'
        ];
    }

    public function LGlobal()
    {
        $this->content['css_add'][] = [
            'global.css'
        ];

        $this->content['js_add'][] = [
            'global.js'
        ];
    }
}
