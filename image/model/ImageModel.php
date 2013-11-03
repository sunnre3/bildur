<?php

namespace image\model;

require_once('./image/model/Image.php');
require_once('./image/model/ImageDAL.php');

class ImageModel {
	private static $NAME = 'name';
	private static $TMP_NAME = 'tmp_name';
	private static $FILESIZE = 'size';
	private static $MIME_TYPE = 'type';

	/**
	 * Private array to hold all the
	 * MIME types we allow to be uploaded.
	 * @var string[]
	 */
	private $allowedMIMEs;

	/**
	 * ImageDAL to save and retrieve from database.
	 * @var [type]
	 */
	private $imageDAL;

	/**
	 * Populates $allowedMIMEs.
	 */
	public function __construct() {
		//Set allowedMIMEs.
		$this->allowedMIMEs = array(
			'image/png',
			'image/gif',
			'image/jpg',
			'/image/jpeg'
			);

		//Initiate ImageDAL.
		$this->imageDAL = new \image\model\ImageDAL();
	}

	/**
	 * Saves an Image object to the database.	
	 * @param  \image\model\Image $image
	 * @return void
	 */
	public function saveImage(\image\model\Image $image) {
		//Save to database.
		$this->imageDAL->addImage($image);
	}

	/**
	 * This method takes a file that has been uploaded
	 * and verifies that the file is indeed an image
	 * within all correct boundaries (filesize, etc).
	 * @param  \image\model\Image
	 * @return void
	 */
	public function validateImage($file) {
		//Get the MIME type.
		$mime = $this->getMIMEType($file[self::$TMP_NAME]);

		//Save the validated MIME type
		//to the original object.
		$file[self::$MIME_TYPE] = $mime;

		//Get the file extension.
		$extension = '.' . $this->getExtension($file);

		//Save the file and get the new filename.
		$filename = $this->save($file[self::$TMP_NAME], $extension);

		//Generate thumbnail.
		$thumbnail = $this->generateThumbnail(UPLOAD_PATH . $filename);

		//Return the filename.
		return \image\model\Image::uploaded(UPLOAD_PATH . $filename,
											UPLOAD_PATH . $thumbnail);
	}

	/**
	 * This method generates and saves a thumbnail.
	 * Default size is: 200x200px.
	 * @param  string
	 * @return string filename
	 */
	public function generateThumbnail($filepath) {
		//Get an image resource.
		$image = $this->getGDObject($filepath);

		//Old width and height.
		$old_width = imagesx($image);
		$old_height = imagesy($image);

		//New width and height.
		$new_width = 200;
		$new_height = 200;

		//Create a temporary image.
		$tmp_image = imagecreatetruecolor($new_width, $new_height);

		//Copy and resize original.
		imagecopyresized($tmp_image, $image, 0, 0, 0, 0,
			$new_width, $new_height, $old_width, $old_height);

		//Get a filename for the thumbnail.
		$filename = pathinfo($filepath, PATHINFO_FILENAME)
			. $new_width . 'px_' . $new_height . 'px.jpg';

		//Save the thumbnail.
		imagejpeg($tmp_image, UPLOAD_PATH . $filename);

		//Return the filename.
		return $filename;
	}

	/**
	 * This method generates a new (hopefully) unique
	 * filename and saves it to our directory.
	 * @param  string $filepath
	 * @param  string $extension
	 * @return string filename
	 */
	private function save($filepath, $extension) {
		//Generate a new unique filename.
		$filename = md5(rand(0, time())) . $extension;

		//Our new filepath.
		$new_filepath = UPLOAD_PATH . $filename;

		//Save it to our directory.
		//If it comes back as false
		//we throw an exception because
		//something went wrong.
		if(!rename($filepath, $new_filepath))
			throw new \Exception('ImageModel::save() failed: couldn\'t save file');

		//If everything went right we return the new filename.
		return $filename;
	}

	/**
	 * Private helper method for generateThumbnail()
	 * to get a GD resource. This method looks at the file's
	 * extension to return correct resource type.
	 * @param  string $filepath
	 * @return image resource
	 */
	private function getGDObject($filepath) {
		//Get the file extension.
		$extension = pathinfo($filepath, PATHINFO_EXTENSION);

		//Generic image object.
		$image = null;

		//If the file is a .jpg file...
		if($extension == 'jpg' || $extension == 'jpeg')
			$image = imagecreatefromjpeg($filepath);

		//Else if the file is a .png file...
		elseif($extension == 'png')
			$image = imagecreatefrompng($filepath);

		//Else if the file is a .gif file...
		elseif($extension == 'gif')
			$image = imagecreatefromgif($filepath);

		//If $image is still null that means
		//that we couldn't retrieve an image resource
		//from the given filepath which will result
		//in an exception.
		if($image == null)
			throw new \Exception('ImageModel::getGDObject() failed: couldn\'t determine image resource');

		//Else we return it.
		return $image;
	}

	/**
	 * Using the system it returns the MIME type for
	 * a file.
	 * @param  string $filepath path to file
	 * @return string           MIME type
	 */
	private function getMIMEType($filepath) {
		//Make sure that it's a regular file.
		if(!is_file($filepath))
			throw new \Exception('ImageModel::getMIMEType() failed: invalid file');

		//FileInfo.
		$fileInfo = finfo_open(FILEINFO_MIME_TYPE);

		//MIME type.
		$mime = finfo_file($fileInfo, $filepath);

		//Close the resource.
		finfo_close($fileInfo);

		//Return the MIME.
		return $mime;
	}

	/**
	 * Returns the file extension for a given
	 * file.
	 * @param  string $filepath path to file
	 * @return string           file extension
	 */
	private function getExtension($file) {
		//Create an empty string.
		$extension = "";

		//Get extension to MIME mapping.
		$extMapping = $this->getExtensionToMIMETypeMapping();

		//Loop through the extensions.
		foreach($extMapping as $ext => $mimeType) {
			if($mimeType == $file[self::$MIME_TYPE]) {
				$extension = $ext;
			}
		}

		//If we still don't have an extension,
		//then we try pathinfo()
		if($extension == "")
			$extension = pathinfo($file[self::$TMP_NAME], PATHINFO_EXTENSION);

		//And if we still don't have one, we draw
		//the conclusion that the file is invalid
		//and throw an exception.
		if($extension == "")
			throw new \Exception('ImageModel::getExtension() failed: invalid file');
		
		//Otherwise return the extension.
		return $extension;
	}

	/**
	 * Courtesy of user Twisted1919@stackoverflow
	 * @return string[]
	 */
	private function getExtensionToMIMETypeMapping() {
		return array(
	        'ai'=>'application/postscript',
	        'aif'=>'audio/x-aiff',
	        'aifc'=>'audio/x-aiff',
	        'aiff'=>'audio/x-aiff',
	        'anx'=>'application/annodex',
	        'asc'=>'text/plain',
	        'au'=>'audio/basic',
	        'avi'=>'video/x-msvideo',
	        'axa'=>'audio/annodex',
	        'axv'=>'video/annodex',
	        'bcpio'=>'application/x-bcpio',
	        'bin'=>'application/octet-stream',
	        'bmp'=>'image/bmp',
	        'c'=>'text/plain',
	        'cc'=>'text/plain',
	        'ccad'=>'application/clariscad',
	        'cdf'=>'application/x-netcdf',
	        'class'=>'application/octet-stream',
	        'cpio'=>'application/x-cpio',
	        'cpt'=>'application/mac-compactpro',
	        'csh'=>'application/x-csh',
	        'css'=>'text/css',
	        'csv'=>'text/csv',
	        'dcr'=>'application/x-director',
	        'dir'=>'application/x-director',
	        'dms'=>'application/octet-stream',
	        'doc'=>'application/msword',
	        'drw'=>'application/drafting',
	        'dvi'=>'application/x-dvi',
	        'dwg'=>'application/acad',
	        'dxf'=>'application/dxf',
	        'dxr'=>'application/x-director',
	        'eps'=>'application/postscript',
	        'etx'=>'text/x-setext',
	        'exe'=>'application/octet-stream',
	        'ez'=>'application/andrew-inset',
	        'f'=>'text/plain',
	        'f90'=>'text/plain',
	        'flac'=>'audio/flac',
	        'fli'=>'video/x-fli',
	        'flv'=>'video/x-flv',
	        'gif'=>'image/gif',
	        'gtar'=>'application/x-gtar',
	        'gz'=>'application/x-gzip',
	        'h'=>'text/plain',
	        'hdf'=>'application/x-hdf',
	        'hh'=>'text/plain',
	        'hqx'=>'application/mac-binhex40',
	        'htm'=>'text/html',
	        'html'=>'text/html',
	        'ice'=>'x-conference/x-cooltalk',
	        'ief'=>'image/ief',
	        'iges'=>'model/iges',
	        'igs'=>'model/iges',
	        'ips'=>'application/x-ipscript',
	        'ipx'=>'application/x-ipix',
	        'jpe'=>'image/jpeg',
	        'jpeg'=>'image/jpeg',
	        'jpg'=>'image/jpeg',
	        'js'=>'application/x-javascript',
	        'kar'=>'audio/midi',
	        'latex'=>'application/x-latex',
	        'lha'=>'application/octet-stream',
	        'lsp'=>'application/x-lisp',
	        'lzh'=>'application/octet-stream',
	        'm'=>'text/plain',
	        'man'=>'application/x-troff-man',
	        'me'=>'application/x-troff-me',
	        'mesh'=>'model/mesh',
	        'mid'=>'audio/midi',
	        'midi'=>'audio/midi',
	        'mif'=>'application/vnd.mif',
	        'mime'=>'www/mime',
	        'mov'=>'video/quicktime',
	        'movie'=>'video/x-sgi-movie',
	        'mp2'=>'audio/mpeg',
	        'mp3'=>'audio/mpeg',
	        'mpe'=>'video/mpeg',
	        'mpeg'=>'video/mpeg',
	        'mpg'=>'video/mpeg',
	        'mpga'=>'audio/mpeg',
	        'ms'=>'application/x-troff-ms',
	        'msh'=>'model/mesh',
	        'nc'=>'application/x-netcdf',
	        'oga'=>'audio/ogg',
	        'ogg'=>'audio/ogg',
	        'ogv'=>'video/ogg',
	        'ogx'=>'application/ogg',
	        'oda'=>'application/oda',
	        'pbm'=>'image/x-portable-bitmap',
	        'pdb'=>'chemical/x-pdb',
	        'pdf'=>'application/pdf',
	        'pgm'=>'image/x-portable-graymap',
	        'pgn'=>'application/x-chess-pgn',
	        'png'=>'image/png',
	        'pnm'=>'image/x-portable-anymap',
	        'pot'=>'application/mspowerpoint',
	        'ppm'=>'image/x-portable-pixmap',
	        'pps'=>'application/mspowerpoint',
	        'ppt'=>'application/mspowerpoint',
	        'ppz'=>'application/mspowerpoint',
	        'pre'=>'application/x-freelance',
	        'prt'=>'application/pro_eng',
	        'ps'=>'application/postscript',
	        'qt'=>'video/quicktime',
	        'ra'=>'audio/x-realaudio',
	        'ram'=>'audio/x-pn-realaudio',
	        'ras'=>'image/cmu-raster',
	        'rgb'=>'image/x-rgb',
	        'rm'=>'audio/x-pn-realaudio',
	        'roff'=>'application/x-troff',
	        'rpm'=>'audio/x-pn-realaudio-plugin',
	        'rtf'=>'text/rtf',
	        'rtx'=>'text/richtext',
	        'scm'=>'application/x-lotusscreencam',
	        'set'=>'application/set',
	        'sgm'=>'text/sgml',
	        'sgml'=>'text/sgml',
	        'sh'=>'application/x-sh',
	        'shar'=>'application/x-shar',
	        'silo'=>'model/mesh',
	        'sit'=>'application/x-stuffit',
	        'skd'=>'application/x-koan',
	        'skm'=>'application/x-koan',
	        'skp'=>'application/x-koan',
	        'skt'=>'application/x-koan',
	        'smi'=>'application/smil',
	        'smil'=>'application/smil',
	        'snd'=>'audio/basic',
	        'sol'=>'application/solids',
	        'spl'=>'application/x-futuresplash',
	        'spx'=>'audio/ogg',
	        'src'=>'application/x-wais-source',
	        'step'=>'application/STEP',
	        'stl'=>'application/SLA',
	        'stp'=>'application/STEP',
	        'sv4cpio'=>'application/x-sv4cpio',
	        'sv4crc'=>'application/x-sv4crc',
	        'swf'=>'application/x-shockwave-flash',
	        't'=>'application/x-troff',
	        'tar'=>'application/x-tar',
	        'tcl'=>'application/x-tcl',
	        'tex'=>'application/x-tex',
	        'texi'=>'application/x-texinfo',
	        'texinfo'=>'application/x-texinfo',
	        'tif'=>'image/tiff',
	        'tiff'=>'image/tiff',
	        'tr'=>'application/x-troff',
	        'tsi'=>'audio/TSP-audio',
	        'tsp'=>'application/dsptype',
	        'tsv'=>'text/tab-separated-values',
	        'txt'=>'text/plain',
	        'unv'=>'application/i-deas',
	        'ustar'=>'application/x-ustar',
	        'vcd'=>'application/x-cdlink',
	        'vda'=>'application/vda',
	        'viv'=>'video/vnd.vivo',
	        'vivo'=>'video/vnd.vivo',
	        'vrml'=>'model/vrml',
	        'wav'=>'audio/x-wav',
	        'wrl'=>'model/vrml',
	        'xbm'=>'image/x-xbitmap',
	        'xlc'=>'application/vnd.ms-excel',
	        'xll'=>'application/vnd.ms-excel',
	        'xlm'=>'application/vnd.ms-excel',
	        'xls'=>'application/vnd.ms-excel',
	        'xlw'=>'application/vnd.ms-excel',
	        'xml'=>'application/xml',
	        'xpm'=>'image/x-xpixmap',
	        'xspf'=>'application/xspf+xml',
	        'xwd'=>'image/x-xwindowdump',
	        'xyz'=>'chemical/x-pdb',
	        'zip'=>'application/zip',
	    );
	}
}