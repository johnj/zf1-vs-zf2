<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Pdf
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: DrawingTest.php 20469 2010-01-21 16:09:24Z alexander $
 */

/** Zend_Pdf */
require_once 'Zend/Pdf.php';

/** Zend_Pdf_Page */
require_once 'Zend/Pdf/Page.php';

/** Zend_Pdf_Color_GrayScale */
require_once 'Zend/Pdf/Color/GrayScale.php';

/** Zend_Pdf_Color_Cmyk */
require_once 'Zend/Pdf/Color/Cmyk.php';

/** Zend_Pdf_Color_Rgb */
require_once 'Zend/Pdf/Color/Rgb.php';

/** Zend_Pdf_Color_Html */
require_once 'Zend/Pdf/Color/Html.php';

/** Zend_Pdf_Image */
require_once 'Zend/Pdf/Image.php';

/** Zend_Pdf_Font */
require_once 'Zend/Pdf/Font.php';


/** PHPUnit Test Case */
require_once 'PHPUnit/Framework/TestCase.php';

/**
 * @category   Zend
 * @package    Zend_Pdf
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Pdf
 */
class Zend_Pdf_DrawingTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        date_default_timezone_set('GMT');
    }

    public function testDrawing()
    {
        $pdf = new Zend_Pdf();

        // Add new page generated by Zend_Pdf object (page is attached to the specified the document)
        $pdf->pages[] = ($page1 = $pdf->newPage('A4'));

        // Add new page generated by Zend_Pdf_Page object (page is not attached to the document)
        $pdf->pages[] = ($page2 = new Zend_Pdf_Page(Zend_Pdf_Page::SIZE_LETTER_LANDSCAPE));

        // Add new page generated by Zend_Pdf_Page object (page is attached to the document)
        $pdf->pages[] = ($page3 = $pdf->newPage('A4'));

        // Create new font
        $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);

        // Apply font and draw text
        $page1->setFont($font, 36)
              ->setFillColor(Zend_Pdf_Color_Html::color('#9999cc'))
              ->drawText('Helvetica 36 text string', 60, 500);

        // Use font object for another page
        $page2->setFont($font, 24)
              ->drawText('Helvetica 24 text string', 60, 500);

        // Use another font
        $page2->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 32)
              ->drawText('Times-Roman 32 text string', 60, 450);

        // Draw rectangle
        $page2->setFillColor(new Zend_Pdf_Color_GrayScale(0.8))
              ->setLineColor(new Zend_Pdf_Color_GrayScale(0.2))
              ->setLineDashingPattern(array(3, 2, 3, 4), 1.6)
              ->drawRectangle(60, 400, 500, 350);

        // Draw rounded rectangle
        $page2->setFillColor(new Zend_Pdf_Color_GrayScale(0.9))
              ->setLineColor(new Zend_Pdf_Color_GrayScale(0.5))
              ->setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID)
              ->drawRoundedRectangle(425, 350, 475, 400, 20);

        // Draw circle
        $page2->setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID)
              ->setFillColor(new Zend_Pdf_Color_Rgb(1, 0, 0))
              ->drawCircle(85, 375, 25);

        // Draw sectors
        $page2->drawCircle(200, 375, 25, 2*M_PI/3, -M_PI/6)
              ->setFillColor(new Zend_Pdf_Color_Cmyk(1, 0, 0, 0))
              ->drawCircle(200, 375, 25, M_PI/6, 2*M_PI/3)
              ->setFillColor(new Zend_Pdf_Color_Rgb(1, 1, 0))
              ->drawCircle(200, 375, 25, -M_PI/6, M_PI/6);

        // Draw ellipse
        $page2->setFillColor(new Zend_Pdf_Color_Html('Red'))
              ->drawEllipse(250, 400, 400, 350)
              ->setFillColor(new Zend_Pdf_Color_Cmyk(1, 0, 0, 0))
              ->drawEllipse(250, 400, 400, 350, M_PI/6, 2*M_PI/3)
              ->setFillColor(new Zend_Pdf_Color_Rgb(1, 1, 0))
              ->drawEllipse(250, 400, 400, 350, -M_PI/6, M_PI/6);

        // Draw and fill polygon
        $page2->setFillColor(new Zend_Pdf_Color_Rgb(1, 0, 1));
        $x = array();
        $y = array();
        for ($count = 0; $count < 8; $count++) {
            $x[] = 140 + 25*cos(3*M_PI_4*$count);
            $y[] = 375 + 25*sin(3*M_PI_4*$count);
        }
        $page2->drawPolygon($x, $y,
                            Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE,
                            Zend_Pdf_Page::FILL_METHOD_EVEN_ODD);

        // Draw line
        $page2->setLineWidth(0.5)
              ->drawLine(60, 375, 500, 375);

        // -----------------------------------------------------------------------------------
        $page3->translate(200, 10)
              ->rotate(10, 10, M_PI_2/9)
              ->scale(0.7, 1.2)
              ->skew(60, 350, M_PI_2/9, -M_PI_2/9);

        // Use font object for another page
        $page3->setFont($font, 24)
              ->drawText('Helvetica 24 text string', 60, 500);

        // Use another font
        $page3->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_TIMES), 32)
              ->drawText('Times-Roman 32 text string', 60, 450);

        // Draw rectangle
        $page3->setFillColor(new Zend_Pdf_Color_GrayScale(0.8))
              ->setLineColor(new Zend_Pdf_Color_GrayScale(0.2))
              ->setLineDashingPattern(array(3, 2, 3, 4), 1.6)
              ->drawRectangle(60, 400, 500, 350);

        // Draw rounded rectangle
        $page2->setFillColor(new Zend_Pdf_Color_GrayScale(0.9))
              ->setLineColor(new Zend_Pdf_Color_GrayScale(0.5))
              ->setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID)
              ->drawRoundedRectangle(425, 350, 475, 400, 20);


        // Draw circle
        $page3->setLineDashingPattern(Zend_Pdf_Page::LINE_DASHING_SOLID)
              ->setFillColor(new Zend_Pdf_Color_Rgb(1, 0, 0))
              ->drawCircle(85, 375, 25);

        // Draw sectors
        $page3->drawCircle(200, 375, 25, 2*M_PI/3, -M_PI/6)
              ->setFillColor(new Zend_Pdf_Color_Cmyk(1, 0, 0, 0))
              ->drawCircle(200, 375, 25, M_PI/6, 2*M_PI/3)
              ->setFillColor(new Zend_Pdf_Color_Rgb(1, 1, 0))
              ->drawCircle(200, 375, 25, -M_PI/6, M_PI/6);

        // Draw ellipse
        $page3->setFillColor(new Zend_Pdf_Color_Html('Red'))
              ->drawEllipse(250, 400, 400, 350)
              ->setFillColor(new Zend_Pdf_Color_Cmyk(1, 0, 0, 0))
              ->drawEllipse(250, 400, 400, 350, M_PI/6, 2*M_PI/3)
              ->setFillColor(new Zend_Pdf_Color_Rgb(1, 1, 0))
              ->drawEllipse(250, 400, 400, 350, -M_PI/6, M_PI/6);

        // Draw and fill polygon
        $page3->setFillColor(new Zend_Pdf_Color_Rgb(1, 0, 1));
        $x = array();
        $y = array();
        for ($count = 0; $count < 8; $count++) {
            $x[] = 140 + 25*cos(3*M_PI_4*$count);
            $y[] = 375 + 25*sin(3*M_PI_4*$count);
        }
        $page3->drawPolygon($x, $y,
                            Zend_Pdf_Page::SHAPE_DRAW_FILL_AND_STROKE,
                            Zend_Pdf_Page::FILL_METHOD_EVEN_ODD);

        // Draw line
        $page3->setLineWidth(0.5)
              ->drawLine(60, 375, 500, 375);


        $pdf->save(dirname(__FILE__) . '/_files/output.pdf');
        unset($pdf);

        $pdf1 = Zend_Pdf::load(dirname(__FILE__) . '/_files/output.pdf');
        $this->assertTrue($pdf1 instanceof Zend_Pdf);
        unset($pdf1);

        unlink(dirname(__FILE__) . '/_files/output.pdf');
    }

    public function testImageDrawing()
    {
        $pdf = new Zend_Pdf();

        // Add new page generated by Zend_Pdf object (page is attached to the specified the document)
        $pdf->pages[] = ($page = $pdf->newPage('A4'));


        $stampImagePNG = Zend_Pdf_Image::imageWithPath(dirname(__FILE__) . '/_files/stamp.png');
        $this->assertTrue($stampImagePNG instanceof Zend_Pdf_Resource_Image);

        $page->saveGS()
             ->clipCircle(250, 500, 50)
             ->drawImage($stampImagePNG, 200, 450, 300, 550)
             ->restoreGS();


        $stampImageTIFF = Zend_Pdf_Image::imageWithPath(dirname(__FILE__) . '/_files/stamp.tif');
        $this->assertTrue($stampImageTIFF instanceof Zend_Pdf_Resource_Image);

        $page->saveGS()
             ->clipCircle(325, 500, 50)
             ->drawImage($stampImagePNG, 275, 450, 375, 550)
             ->restoreGS();

        $jpegSupported = false;
        if (function_exists('gd_info')) {
            $info = gd_info();
            if (isset($info['JPG Support'])) {
                $jpegSupported = $info['JPG Support'];
            } elseif (isset($info['JPEG Support'])) {
                $jpegSupported = $info['JPEG Support'];
            }
        }
        if ($jpegSupported) {
            $stampImageJPG = Zend_Pdf_Image::imageWithPath(dirname(__FILE__) . '/_files/stamp.jpg');

            $this->assertTrue($stampImageJPG instanceof Zend_Pdf_Resource_Image);

            $page->saveGS()
                 ->clipCircle(287.5, 440, 50)
                 ->drawImage($stampImageJPG, 237.5, 390, 337.5, 490)
                 ->restoreGS();

            $page->saveGS()
                 ->clipCircle(250, 500, 50)
                 ->clipCircle(287.5, 440, 50)
                 ->drawImage($stampImagePNG, 200, 450, 300, 550)
                 ->restoreGS();
        }

        $pdf->save(dirname(__FILE__) . '/_files/output.pdf');
        unset($pdf);

        $pdf1 = Zend_Pdf::load(dirname(__FILE__) . '/_files/output.pdf');
        $this->assertTrue($pdf1 instanceof Zend_Pdf);
        unset($pdf1);

        unlink(dirname(__FILE__) . '/_files/output.pdf');
    }

    public function testFontDrawing()
    {
        if (PHP_OS == 'AIX') {
            $this->markTestSkipped('Not supported on AIX');
        }

        $pdf = new Zend_Pdf();

        $fontsList = array(Zend_Pdf_Font::FONT_COURIER,
                          Zend_Pdf_Font::FONT_COURIER_BOLD,
                          Zend_Pdf_Font::FONT_COURIER_BOLD_ITALIC,
                          Zend_Pdf_Font::FONT_COURIER_BOLD_OBLIQUE,
                          Zend_Pdf_Font::FONT_COURIER_ITALIC,
                          Zend_Pdf_Font::FONT_COURIER_OBLIQUE,
                          Zend_Pdf_Font::FONT_HELVETICA,
                          Zend_Pdf_Font::FONT_HELVETICA_BOLD,
                          Zend_Pdf_Font::FONT_HELVETICA_BOLD_ITALIC,
                          Zend_Pdf_Font::FONT_HELVETICA_BOLD_OBLIQUE,
                          Zend_Pdf_Font::FONT_HELVETICA_ITALIC,
                          Zend_Pdf_Font::FONT_HELVETICA_OBLIQUE,
                          Zend_Pdf_Font::FONT_TIMES,
                          Zend_Pdf_Font::FONT_TIMES_BOLD,
                          Zend_Pdf_Font::FONT_TIMES_BOLD_ITALIC,
                          Zend_Pdf_Font::FONT_TIMES_ITALIC,
                          Zend_Pdf_Font::FONT_TIMES_ROMAN);

        $titleFont = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_COURIER_BOLD_OBLIQUE);

        foreach ($fontsList as $fontName) {
            // Add new page generated by Zend_Pdf object (page is attached to the specified the document)
            $pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE));

            $font = Zend_Pdf_Font::fontWithName($fontName);
            $this->assertTrue($font instanceof Zend_Pdf_Resource_Font);

            $page->setFont($titleFont, 10)
                 ->drawText($font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en') . ':', 100, 400);

            $page->setFont($font, 20);
            $page->drawText("'The quick brown fox jumps over the lazy dog'", 100, 360);

            $ascent = $font->getAscent();
            $this->assertTrue( abs(1 - $font->getCoveredPercentage('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxwz')) < 0.00001 );
            $descent = $font->getDescent();

            $font->getFontName(Zend_Pdf_Font::NAME_FULL, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_FAMILY, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_PREFERRED_FAMILY, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_STYLE, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_PREFERRED_STYLE, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_DESCRIPTION, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_SAMPLE_TEXT, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_ID, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_VERSION, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_CID_NAME, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_DESIGNER, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_DESIGNER_URL, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_MANUFACTURER, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_VENDOR_URL, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_COPYRIGHT, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_TRADEMARK, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_LICENSE, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_LICENSE_URL, 'en');

            $type       = $font->getFontType();
            $lineGap    = $font->getLineGap();
            $lineHeight = $font->getLineHeight();
            $this->assertTrue($font->getResource() instanceof Zend_Pdf_Element_Object);
            $font->getStrikePosition();
            $font->getStrikeThickness();
            $font->getUnderlinePosition();
            $font->getUnitsPerEm();
            $font->widthForGlyph(10);
        }

        $nonAlphabeticalPhonts =
                 array(Zend_Pdf_Font::FONT_SYMBOL =>
                                "\x00\x20\x00\x21\x22\x00\x00\x23\x22\x03\x00\x25\x00\x26\x22\x0b\x00\x28\x00\x29\x22\x17\x00\x2b\x00\x2c\x22\x12\x00\x2e\x00\x2f\x00\x30\x00\x31\x00\x32\x00\x33\x00\x34\x00\x35\x00\x36\x00\x37\x00\x38\x00\x39\x00\x3a\x00\x3b\x00\x3c\x00\x3d\x00\x3e\x00\x3f\x22\x45\x03\x91\x03\x92\x03\xa7\x22\x06\x03\x95\x03\xa6",
                       Zend_Pdf_Font::FONT_ZAPFDINGBATS =>
                                "\x00\x20\x27\x01\x27\x02\x27\x03\x27\x04\x26\x0e\x27\x06\x27\x07\x27\x08\x27\x09\x26\x1b\x26\x1e\x27\x0c\x27\x0d\x27\x0e\x27\x0f\x27\x10\x27\x11\x27\x12\x27\x13\x27\x14\x27\x15\x27\x16\x27\x17\x27\x18\x27\x19\x27\x1a");
        foreach ($nonAlphabeticalPhonts as $fontName => $example) {
            // Add new page generated by Zend_Pdf object (page is attached to the specified the document)
            $pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE));

            $font = Zend_Pdf_Font::fontWithName($fontName);
            $this->assertTrue($font instanceof Zend_Pdf_Resource_Font);

            $page->setFont($titleFont, 10)
                 ->drawText($font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en') . ':', 100, 400);

            $page->setFont($font, 20)
                 ->drawText($example, 100, 360, 'UTF-16BE');

            $ascent = $font->getAscent();
            $this->assertTrue( abs(1 - $font->getCoveredPercentage($example, 'UTF-16BE')) < 0.00001 );
            $descent = $font->getDescent();

            $font->getFontName(Zend_Pdf_Font::NAME_FULL, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_FAMILY, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_PREFERRED_FAMILY, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_STYLE, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_PREFERRED_STYLE, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_DESCRIPTION, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_SAMPLE_TEXT, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_ID, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_VERSION, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_CID_NAME, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_DESIGNER, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_DESIGNER_URL, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_MANUFACTURER, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_VENDOR_URL, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_COPYRIGHT, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_TRADEMARK, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_LICENSE, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_LICENSE_URL, 'en');

            $type       = $font->getFontType();
            $lineGap    = $font->getLineGap();
            $lineHeight = $font->getLineHeight();
            $this->assertTrue($font->getResource() instanceof Zend_Pdf_Element_Object);
            $font->getStrikePosition();
            $font->getStrikeThickness();
            $font->getUnderlinePosition();
            $font->getUnitsPerEm();
            $font->widthForGlyph(10);
        }

        $TTFFontsList = array('VeraBd.ttf',
                              'VeraBI.ttf',
                              'VeraIt.ttf',
                              'VeraMoBd.ttf',
                              'VeraMoBI.ttf',
                              'VeraMoIt.ttf',
                              'VeraMono.ttf',
                              'VeraSeBd.ttf',
                              'VeraSe.ttf',
                              'Vera.ttf');

        foreach ($TTFFontsList as $fontName) {
            // Add new page generated by Zend_Pdf object (page is attached to the specified the document)
            $pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE));

            $font = Zend_Pdf_Font::fontWithPath(dirname(__FILE__) . '/_fonts/' . $fontName);
            $this->assertTrue($font instanceof Zend_Pdf_Resource_Font);

            $page->setFont($titleFont, 10)
                 ->drawText($font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en') . ':', 100, 400);

            $page->setFont($font, 20)
                 ->drawText("'The quick brown fox jumps over the lazy dog'", 100, 360);

            $ascent = $font->getAscent();
            $this->assertTrue( abs(1 - $font->getCoveredPercentage('ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxwz')) < 0.00001 );
            $descent = $font->getDescent();

            $font->getFontName(Zend_Pdf_Font::NAME_FULL, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_FAMILY, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_PREFERRED_FAMILY, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_STYLE, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_PREFERRED_STYLE, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_DESCRIPTION, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_SAMPLE_TEXT, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_ID, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_VERSION, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_CID_NAME, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_DESIGNER, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_DESIGNER_URL, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_MANUFACTURER, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_VENDOR_URL, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_COPYRIGHT, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_TRADEMARK, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_LICENSE, 'en');
            $font->getFontName(Zend_Pdf_Font::NAME_LICENSE_URL, 'en');

            $type       = $font->getFontType();
            $lineGap    = $font->getLineGap();
            $lineHeight = $font->getLineHeight();
            $this->assertTrue($font->getResource() instanceof Zend_Pdf_Element_Object);
            $font->getStrikePosition();
            $font->getStrikeThickness();
            $font->getUnderlinePosition();
            $font->getUnitsPerEm();
            $font->widthForGlyph(10);
        }

        $pdf->save(dirname(__FILE__) . '/_files/output.pdf');
        unset($pdf);

        $pdf1 = Zend_Pdf::load(dirname(__FILE__) . '/_files/output.pdf');
        $this->assertTrue($pdf1 instanceof Zend_Pdf);
        unset($pdf1);

        unlink(dirname(__FILE__) . '/_files/output.pdf');
    }

    public function testFontExtracting()
    {
        if (PHP_OS == 'AIX') {
            $this->markTestSkipped('Not supported on AIX');
        }

        $pdf = new Zend_Pdf();

        $fontsList = array(Zend_Pdf_Font::FONT_COURIER,
                           Zend_Pdf_Font::FONT_HELVETICA_BOLD,
                           Zend_Pdf_Font::FONT_TIMES_BOLD_ITALIC);

        foreach ($fontsList as $fontName) {
            // Add new page generated by Zend_Pdf object (page is attached to the specified the document)
            $pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE));

            $font = Zend_Pdf_Font::fontWithName($fontName);
            $page->setFont($font, 10)
                 ->drawText($font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en') . ':', 100, 400);
            $page->setFont($font, 20)
                 ->drawText("'The quick brown fox jumps over the lazy dog'", 100, 360);

            $type = $font->getFontType();
        }

        $TTFFontsList = array('VeraBd.ttf',
                              'VeraBI.ttf',
                              'VeraIt.ttf',
                              'VeraMoBd.ttf',
                              'VeraMoBI.ttf',
                              'VeraMoIt.ttf',
                              'VeraMono.ttf',
                              'VeraSeBd.ttf',
                              'VeraSe.ttf',
                              'Vera.ttf');

        foreach ($TTFFontsList as $fontName) {
            // Add new page generated by Zend_Pdf object (page is attached to the specified the document)
            $pdf->pages[] = ($page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4_LANDSCAPE));

            $font = Zend_Pdf_Font::fontWithPath(dirname(__FILE__) . '/_fonts/' . $fontName);
            $page->setFont($font, 10)
                 ->drawText($font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en', 'CP1252') . ':', 100, 400);
            $page->setFont($font, 20)
                 ->drawText("'The quick brown fox jumps over the lazy dog'", 100, 360);
            $type = $font->getFontType();
        }

        $pdf->save(dirname(__FILE__) . '/_files/output.pdf');
        unset($pdf);

        $pdf1 = Zend_Pdf::load(dirname(__FILE__) . '/_files/output.pdf');

        $newPages = array();

        $fontList  = array();
        $fontNames = array();
        foreach ($pdf1->pages as $page) {
            $pageFonts = $page->extractFonts();
            foreach ($pageFonts as $font) {
                $fontList[]  = $font;
                $fontNames[] = $font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en', 'UTF-8');
            }
        }

        $this->assertEquals(array(Zend_Pdf_Font::FONT_COURIER,
                                  Zend_Pdf_Font::FONT_HELVETICA_BOLD,
                                  Zend_Pdf_Font::FONT_TIMES_BOLD_ITALIC,
                                  'BitstreamVeraSans-Bold',
                                  'BitstreamVeraSans-BoldOblique',
                                  'BitstreamVeraSans-Oblique',
                                  'BitstreamVeraSansMono-Bold',
                                  'BitstreamVeraSansMono-BoldOb',
                                  'BitstreamVeraSansMono-Oblique',
                                  'BitstreamVeraSansMono-Roman',
                                  'BitstreamVeraSerif-Bold',
                                  'BitstreamVeraSerif-Roman',
                                  'BitstreamVeraSans-Roman'),
                            $fontNames);

        $pdf1->pages[] = ($page = $pdf1->newPage(Zend_Pdf_Page::SIZE_A4));
        $yPosition = 700;
        foreach ($fontList as $font) {
            $page->setFont($font, 15)
                 ->drawText("The quick brown fox jumps over the lazy dog", 100, $yPosition);
            $yPosition -= 30;
        }

        $fontNames1 = array();
        foreach ($pdf1->extractFonts() as $font) {
            $fontNames1[] = $font->getFontName(Zend_Pdf_Font::NAME_POSTSCRIPT, 'en', 'UTF-8');
        }
        $this->assertEquals(array(Zend_Pdf_Font::FONT_COURIER,
                                  Zend_Pdf_Font::FONT_HELVETICA_BOLD,
                                  Zend_Pdf_Font::FONT_TIMES_BOLD_ITALIC,
                                  'BitstreamVeraSans-Bold',
                                  'BitstreamVeraSans-BoldOblique',
                                  'BitstreamVeraSans-Oblique',
                                  'BitstreamVeraSansMono-Bold',
                                  'BitstreamVeraSansMono-BoldOb',
                                  'BitstreamVeraSansMono-Oblique',
                                  'BitstreamVeraSansMono-Roman',
                                  'BitstreamVeraSerif-Bold',
                                  'BitstreamVeraSerif-Roman',
                                  'BitstreamVeraSans-Roman'),
                            $fontNames1);

        $page = reset($pdf1->pages);
        $font = $page->extractFont(Zend_Pdf_Font::FONT_COURIER);
        $this->assertTrue($font instanceof Zend_Pdf_Resource_Font_Extracted);

        $font = $page->extractFont(Zend_Pdf_Font::FONT_TIMES_BOLD_ITALIC);
        $this->assertNull($font);


        $font = $pdf1->extractFont(Zend_Pdf_Font::FONT_TIMES_BOLD_ITALIC);
        $this->assertTrue($font instanceof Zend_Pdf_Resource_Font_Extracted);

        $font = $pdf1->extractFont(Zend_Pdf_Font::FONT_TIMES_ROMAN);
        $this->assertNull($font);

        $pdf1->save(dirname(__FILE__) . '/_files/output1.pdf');
        unset($pdf1);


        $pdf2 = Zend_Pdf::load(dirname(__FILE__) . '/_files/output1.pdf');
        $this->assertTrue($pdf2 instanceof Zend_Pdf);
        unset($pdf2);

        unlink(dirname(__FILE__) . '/_files/output.pdf');
        unlink(dirname(__FILE__) . '/_files/output1.pdf');
    }
}
