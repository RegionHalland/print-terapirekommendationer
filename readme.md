# Print Terapirekommendationer 📖
Allows editors to create a PDF with chapters from Terapirekommendationer 

## Installation

### Installing PrinceXML on the server

This plugin uses [PrinceXML](https://www.princexml.com/) to create the PDF file. It needs to be installed on the server. The plugin will look for the installed Prince binary in `/usr/bin/prince`. If it's installed elsewhere, update [this line](https://github.com/RegionHalland/print-terapirekommendationer/blob/master/App.php#L12) with the correct path.

Follow the instructions below or the [official instructions for installing PrinceXML on Ubuntu](https://www.princexml.com/doc-install/#install-debian).

1. Download the installation package somewhere on your server:
```sh
$ cd ~
$ wget https://www.princexml.com/download/prince_12.2-1_ubuntu18.04_amd64.deb
```
All available versions of PrinceXML can be found [here](https://www.princexml.com/download/).

2. Install gdebi on the server:
```sh
$ sudo apt install gdebi
```

3. Install the downloaded package using gdebi:
```sh
$ sudo gdebi ~/prince_12.2-1_ubuntu16.04_i386.deb
```

4. Make sure that PrinceXML is installed. The following command should return a version number and some information about the package:
```sh
$ prince --version
```

### Installing the plugin

5. In your theme or Wordpress site, install the plugin via Composer:
```sh
$ composer require regonhalland/print-terapirekommendationer
```

## Development

Use gulp to build assets. The following gulp tasks are defined:

* `watch` - Watch for changes in the `./src` directory.
* `build` - Minifies and compiles assets into the `./dist` directory.

## Usage

Make sure that the plugin is activated under **Plugins → Print Terapirekommendationer**. 

Go to **Settings → Print Terapirekommendationer**, select the chapters you want to print and click **Ladda ner PDF**. 

The PDF will be generated and downloaded automatically.