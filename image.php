<?php
/**
 * A class designed to take care of the optimization and uploading of images.
 *
 * @author Lauge Rud Knudsen <laugerudknudsen@gmail.com>
 * @version V2
 */
class image {
  private $image;
  private $advanced = false;
  private $extension;
  private $filetype;
  private $isVideo = false;
  
  public function getImage() {
    if ($this->isTransparent($this->image)) {
      imagealphablending($this->image, FALSE);
      imagesavealpha($this->image, TRUE);
    }
    return $this->image;
  }
  public function getFiletype() {
    return $this->filetype;
  }
  
  function __construct($image, $advanced = false, $extension = 'png') {
    $this->image = $image;
    $this->advanced = (!empty($advanced)) ? $advanced : false;
    $this->extension = $extension;
  }
  /**
   * Optimize Image Upload.
   * @param string $dir Path to the folder where the image should be uploaded to
   * @return boolean|string Returns FALSE if filetype is not supported, returns the filetype of the uploaded image otherwise
   */
  public function optimize($dir) {
    FishyUtils::getInstance()->log('Optimizing model thumbnail located at ' . $this->image);
    $output = false;
    if ($this->checkFiletype() === false) {
      return false;
    }
    switch ($this->filetype) {
      case 'svg':
        exec('svgo --enable={removeScriptElement,removeOffCanvasPaths,reusePaths} ' . $this->image . ' -o ' . $dir . 'image.svg -q');
        exec('magick convert -density 128 -resize 512x512 -background transparent ' . $dir . 'image.svg ' . $dir . 'image.png');
        $output = 'svg';
        break;
      case 'mp4':
      case 'webm':
        exec('magick convert ' . $this->image . '[1] -quality ' . JPEGQUALITY . ' ' . $dir . 'image.jpg 2>&1', $output);
        FishyUtils::getInstance()->logExecError($output);
        exec('ffmpeg -an -i ' . $this->image . ' -vcodec libx264 -pix_fmt yuv420p -profile:v baseline -level 3 -max_muxing_queue_size 4096 ' . $dir . 'video.mp4 2>&1', $output);
        FishyUtils::getInstance()->logExecError($output);
        exec('ffmpeg -an -i ' . $this->image . ' -vcodec libvpx -qmin 0 -qmax 50 -crf 10 -b:v 1M -max_muxing_queue_size 4096 ' . $dir . 'video.webm 2>&1', $output);
        FishyUtils::getInstance()->logExecError($output);
        $output = 'jpg';
        break;
      case 'gif':
        exec('gif2apng ' . $this->image . ' ' . $dir . 'image.png 2>&1', $output);
        FishyUtils::getInstance()->logExecError($output);
        $output = 'png';
        break;
      case 'png':
        $output = 'png';
        if ($this->isTransparent() || $this->isApng()) {
          exec('optipng -o' . PNGOPTIMIZATION . ' -out ' . $dir . 'image.png ' . $this->image . ' 2>&1', $output);
          FishyUtils::getInstance()->logExecError($output);
          break;
        }
        //continues to jpg, this is intentional
      case 'jpg':
        if ($output == 'png') {
          $jpg = imagecreatefrompng($this->image);
        } else {
          $jpg = imagecreatefromjpeg($this->image);
        }
        
        imagejpeg($jpg, $dir . 'image.jpg', JPEGQUALITY);
        $output = 'jpg';
        break;
    }
    return $output;
  }
  /**
   * Resize Image For Embed.
   * @param string $dir Path to the folder containing the image to be converted
   */
  public function resizeEmbed($dir) {
    switch ($this->filetype) {
      case 'svg':
        $svgImage = $dir . 'image.png';
        if ($this->isTransparent($svgImage)) {
          $ext = 'png';
        } else {
          $ext = 'jpg';
        }
        break;
      case 'png':
        if ($this->isApng()) {
          $ext = 'gif';
        } else if ($this->isTransparent()) {
          $ext = 'png';
        } else {
          $ext = 'jpg';
        }
        break;
      case 'mp4':
      case 'webm':
        $ext = 'gif';
        break;
      default:
        $ext = $this->filetype;
        break;
    }
    $imageName = $dir . "original." . $this->filetype;
    if ($this->filetype == 'svg') {
      $imageName = $svgImage;
    }
    $thumbnail = $dir . "thumbnail." . $ext;
    if ($this->isVideo) {
      exec('ffmpeg -i ' . $this->image . ' -filter_complex "[0:v] fps=30,scale=64:-1,split [a][b];[a] palettegen [p];[b][p] paletteuse" ' . $thumbnail . ' 2>&1', $output);
      FishyUtils::getInstance()->logExecError($output);
      $imageName = $thumbnail;
    }
    switch ($this->filetype) {
      case "png":
        exec("magick convert -background none $imageName -resize 64x64^ \ -gravity center -extent 64x64 $thumbnail 2>&1", $output);
        FishyUtils::getInstance()->logExecError($output);
        break;
      case "jpg":
        exec("magick convert $imageName -resize 64x64^ \ -gravity center -extent 64x64 $thumbnail 2>&1", $output);
        FishyUtils::getInstance()->logExecError($output);
        break;
      case 'mp4':
      case 'webm':
      case "gif":
        exec("magick convert $imageName -layers Coalesce -resize 64x64 -layers Optimize $thumbnail 2>&1", $output);
        FishyUtils::getInstance()->logExecError($output);
        break;
    }
  }
  /**
   * Is Image Transparent.
   * @return boolean
   */
  private function isTransparent($image = "") {
    if (empty($image)) {
      $image = $this->image;
    }
    if ($this->advanced) {
      $output = $this->isTransparentAdvanced($image);
    } else {
      $val = exec('magick convert ' . $image . ' -format %[opaque] info:');
      switch (strtolower($val)) {
        case 'false':
          $output = true;
          break;
        case NULL:
        default:
          $output = false;
      }
    }
    return $output;
  }
  private function isTransparentAdvanced($image = "") {
    // return imagecolortransparent($image);

    $width = imagesx($image); // Get the width of the image
    $height = imagesy($image); // Get the height of the image

    // We run the image pixel by pixel and as soon as we find a transparent pixel we stop and return true.
    for($i = 0; $i < $width; $i++) {
        for($j = 0; $j < $height; $j++) {
            $rgba = imagecolorat($image, $i, $j);
            $color = imagecolorsforindex($image, $rgba);
            if ($color['alpha'] > 0) {
              return true;
            }
            // if(($rgba & 0x7F000000) >> 24) {
            //     return true;
            // }
        }
    }

    // If we dont find any pixel the function will return false.
    return false;
}
  /**
   * Is Image Animated PNG.
   * @return boolean
   */
  private function isApng() {
    $img_bytes = file_get_contents($this->image);
    if ($img_bytes) {
      if (strpos(substr($img_bytes, 0, strpos($img_bytes, 'IDAT')), 'acTL') !== false) {
        return true;
      }
    }
    return false;
  }
  /**
   * Check Image Filetype.
   * @return boolean|string Returns FALSE if filetype is not supported, returns filetype otherwise
   */
  public function checkFiletype() {
    $output = false;
    if ($this->advanced) {
      $this->filetype = $this->extension;
    } else {
      $ext = exif_imagetype($this->image);
      if ($this->isSvg($this->image)) {
        $this->filetype = 'svg';
      }
      switch ($ext) {
        case IMAGETYPE_PNG:
          $this->filetype = 'png';
          break;
        case IMAGETYPE_JPEG:
          $this->filetype = 'jpg';
          break;
        case IMAGETYPE_GIF:
          $this->filetype = 'gif';
          break;
      }
    }
    if (!isset($this->filetype)) {
      if (ENV === 'local') {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
      } else {
        $finfo = finfo_open(FILEINFO_MIME_TYPE, '/usr/share/misc/magic.mgc');
      }
      
      $mime_type = finfo_file($finfo, $this->image); // check mime type
      finfo_close($finfo);
      switch ($mime_type) {
        case 'video/mp4':
          $this->filetype = 'mp4';
          $this->isVideo = true;
          break;
        case 'video/webm':
          $this->filetype = 'webm';
          $this->isVideo = true;
          break;
      }
    }
    if (isset($this->filetype)) {
      $output = $this->filetype;
    }
    return $output;
  }
  /**
   * Is Image Valid For Upload.
   * @return boolean
   */
  public function isValid() {
    if ($this->advanced) {
      $this->image = imagecreatefromstring($this->image);
    }

    if (!$this->checkFiletype()) {
      trigger_error('Image format is invalid', E_USER_ERROR);
      failed(WEBROOT . '/', 'Image format is invalid');
      return false;
    }
    if ($this->isVideo) {
      $videoAtts = $this->getVideoAttributes();
      $width = $videoAtts['width'];
      $height = $videoAtts['height'];
      if ($width != $height) {
        trigger_error('Video is not 1:1 aspect ratio', E_USER_ERROR);
        failed(WEBROOT . '/', 'Video is not 1:1 aspect ratio');
        return false;
      }
      if ($width < MINIMAGESIZE) {
        trigger_error('Video is smaller than ' . MINIMAGESIZE, E_USER_ERROR);
        failed(WEBROOT . '/', 'Video is smaller than ' . MINIMAGESIZE);
        return false;
      }
      
      $filesize = filesize($this->image);
      $maxFilesize = Helper::getInstance()->convertToBytes(MAXVIDEOFILESIZE, 'mb');
      if ($filesize > $maxFilesize) {
        trigger_error('Video is too large, max filesize is ' . MAXVIDEOFILESIZE, E_USER_ERROR);
        failed(WEBROOT . '/', 'Video is too large, max filesize is ' . MAXVIDEOFILESIZE);
        return false;
      }
    } else if ($this->filetype == 'svg') {
      
    } else {
      if ($this->advanced) {
        $width = imagesx($this->image);
        $height = imagesy($this->image);
      } else {
        $imageSize = getimagesize($this->image);
        $width = $imageSize[0];
        $height = $imageSize[1];
      }
      if ($width != $height) {
        trigger_error('Image is not 1:1 aspect ratio', E_USER_ERROR);
        failed(WEBROOT . '/', 'Image is not 1:1 aspect ratio');
        return false;
      }
      if ($width < MINIMAGESIZE) {
        trigger_error('Image is smaller than ' . MINIMAGESIZE, E_USER_ERROR);
        failed(WEBROOT . '/', 'Image is smaller than ' . MINIMAGESIZE);
        return false;
      }
    }
    
    return true;
  }
  
  protected function getVideoAttributes() {
    $command = FFMPEG_PATH . ' -i ' . $this->image . ' -vstats 2>&1';
    $output = shell_exec($command);

    $regex_sizes = "/Video: ([^,]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; // or : $regex_sizes = "/Video: ([^\r\n]*), ([^,]*), ([0-9]{1,4})x([0-9]{1,4})/"; (code from @1owk3y)
    if (preg_match($regex_sizes, $output, $regs)) {
      $codec = $regs [1] ? $regs [1] : null;
      $width = $regs [3] ? $regs [3] : null;
      $height = $regs [4] ? $regs [4] : null;
    }

    $regex_duration = "/Duration: ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2}).([0-9]{1,2})/";
    if (preg_match($regex_duration, $output, $regs)) {
      $hours = $regs [1] ? $regs [1] : null;
      $mins = $regs [2] ? $regs [2] : null;
      $secs = $regs [3] ? $regs [3] : null;
      $ms = $regs [4] ? $regs [4] : null;
    }

    return array(
      'codec' => $codec,
      'width' => $width,
      'height' => $height,
      'hours' => $hours,
      'mins' => $mins,
      'secs' => $secs,
      'ms' => $ms
    );
  }

  protected function stripScripts() {
    
}
  
  protected function isSvg($filePath) {
    return 'image/svg+xml' === mime_content_type($filePath);
  }

}
