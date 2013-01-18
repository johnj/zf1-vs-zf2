ZF1 vs ZF2
==========

Working comparison between Zend Framework versions 1 &amp; 2

zf1 version: 1.11.1

zf2 version: 2.0.6

benchmarks
==========

Number of function/method calls
-------------------------------
<table>
<tr><td>application</td><td>calls</td></tr>
<tr><td>zf1 skel</td><td>1769</td></tr>
<tr><td>zf2 skel</td><td>6325</td></tr>
</table>

APC disabled
-----------------
<table>
<tr><td>application</td><td>overall latency</td><td>worst method</td><td>worst method latency</td></tr>
<tr><td>zf1 skel</td><td>29ms</td><td>load::Request/Http.php</td><td>1.2ms</td></tr>
<tr><td>zf2 skel</td><td>75ms</td><td>Composer\Autoload\ClassLoader::findFile</td><td>3.7ms</td></tr>
</table>

APC (apc.stat=0)
-----------------
<table>
<tr><td>application</td><td>overall latency</td><td>worst method</td><td>worst method latency</td></tr>
<tr><td>zf1 skel</td><td>10ms</td><td>Zend_Loader::loadFile</td><td>.4ms</td></tr>
<tr><td>zf2 skel</td><td>26ms</td><td>Composer\Autoload\ClassLoader::findFile</td><td>3.2ms</td></tr>
</table>

APC (apc.stat=1)
-----------------
<table>
<tr><td>application</td><td>overall latency</td><td>worst method</td><td>worst method latency</td></tr>
<tr><td>zf1 skel</td><td>12ms</td><td>Zend_Controller_Front::dispatch</td><td>.4ms</td></tr>
<tr><td>zf2 skel</td><td>32ms</td><td>Composer\Autoload\ClassLoader::findFile</td><td>3.5ms</td></tr>
</table>
