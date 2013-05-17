<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imager extends CI_Controller {

	public function __construct()
	{
		parent::__construct();   
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function resize()
	{
		//$this->load->library('s3');
		$uri = explode('/',$this->uri->uri_string());
		$size = explode('x',$uri[2]);
		$image_host = implode('/',array_slice($uri, 3));
		$file_name = 'images/'.preg_replace('#/#', '.', $image_host);

		$ch = file_get_contents('http://'.$image_host);
		$fp = file_put_contents($file_name, $ch);
		$bucketName = 'imageresizer';
		//echo $file_name;
		//$this->s3->putObject($this->s3->inputFile($file_name, false), $bucketName, $file_name, S3::ACL_PUBLIC_READ);

		$config['image_library'] = 'gd2';
		$config['source_image']	= $file_name;
		$config['maintain_ratio'] = TRUE;
		$config['width']	 = $size[0];
		$config['height']	= $size[1];

		$this->load->library('image_lib', $config); 

		$this->image_lib->resize();

		$this->output
		    ->set_content_type('jpeg')
		    ->set_output(file_get_contents($file_name));
	}

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */