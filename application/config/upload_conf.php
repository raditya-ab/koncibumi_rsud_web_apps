<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['article'] = array(
	'upload_path' => '../assets/images/articles/',
	'allowed_types' => 'gif|jpg|png|jpeg',
	'max_size' => '5120',
	// 'max_width' => '1920',
	// 'max_height' => '1080',
	'overwrite' => TRUE,
	'remove_spaces' => TRUE,
	'encrypt_name' => TRUE,
	'file_ext_tolower' => TRUE
);

$config['research'] = array(
    'upload_path' => '../assets/images/research/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => '5120',
    // 'max_width' => '1920',
    // 'max_height' => '1080',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['subresearch'] = array(
    'upload_path' => '../assets/images/subresearch/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => '5120',
    // 'max_width' => '1920',
    // 'max_height' => '1080',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['publication_file'] = array(
    'upload_path' => '../assets/images/publication/',
    'allowed_types' => 'pdf',
    'max_size' => '102400',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['publication_image'] = array(
    'upload_path' => '../assets/images/publication/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => '5120',
    // 'max_width' => '1920',
    // 'max_height' => '1080',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['company'] = array(
    'upload_path' => '../assets/images/companies/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => '5120',
    // 'max_width' => '1920',
    // 'max_height' => '1080',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['upload_image_editor'] = array(
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => '5120',
    // 'max_width' => '1920',
    // 'max_height' => '1080',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['page_section'] = array(
    'upload_path' => '../assets/images/page_section/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => '5120',
    // 'max_width' => '1920',
    // 'max_height' => '1080',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['member'] = array(
    'upload_path' => '../assets/images/member/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => '5000',
    // 'max_width' => '500',
    // 'max_height' => '500',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['team'] = array(
    'upload_path' => '../assets/images/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => '5000',
    // 'max_width' => '500',
    // 'max_height' => '500',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);
$config['expert'] = array(
    'upload_path' => '../assets/images/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'max_size' => '5000',
    // 'max_width' => '500',
    // 'max_height' => '500',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['sponsor'] = array(
    'upload_path' => '../assets/images/sponsor/',
    'allowed_types' => 'gif|jpg|png|jpeg',
    'overwrite' => TRUE,
    'remove_spaces' => TRUE,
    'encrypt_name' => TRUE,
    'file_ext_tolower' => TRUE
);

$config['project'] = array(
	'upload_path' => '../assets/images/product/',
	'allowed_types' => 'gif|jpg|png|jpeg',
	'max_size' => 5 * 1024,
	// 'max_width' => '1920',
	// 'max_height' => '1080',
	'overwrite' => TRUE,
	'remove_spaces' => TRUE,
	'encrypt_name' => TRUE,
	'file_ext_tolower' => TRUE
);

$config['slider'] = array(
	'upload_path' => '../assets/images/slider/',
	'allowed_types' => 'gif|jpg|png|jpeg',
	'max_size' => 10 * 1024,
	// 'max_width' => '4096',
	// 'max_height' => '2304',
	'overwrite' => TRUE,
	'remove_spaces' => TRUE,
	'encrypt_name' => TRUE,
	'file_ext_tolower' => TRUE
);

$config['biglogo'] = array(
	'upload_path' => '../assets/images/',
	'allowed_types' => 'gif|jpg|png|jpeg',
	'max_size' => 5 * 1024,
	// 'max_width' => '4096',
	// 'max_height' => '2304',
	'overwrite' => TRUE,
	'remove_spaces' => TRUE,
	'encrypt_name' => TRUE,
	'file_ext_tolower' => TRUE
);

$config['logo'] = array(
	'upload_path' => '../assets/images/',
	'allowed_types' => 'gif|jpg|png|jpeg',
	'max_size' => 5 * 1024,
	// 'max_width' => '4096',
	// 'max_height' => '2304',
	'overwrite' => TRUE,
	'remove_spaces' => TRUE,
	'encrypt_name' => TRUE,
	'file_ext_tolower' => TRUE
);

$config['favicon'] = array(
	'upload_path' => '../assets/images/',
	'allowed_types' => 'ico|png',
	'max_size' => 5 * 1024,
	// 'max_width' => '4096',
	// 'max_height' => '2304',
	'overwrite' => TRUE,
	'remove_spaces' => TRUE,
	'encrypt_name' => TRUE,
	'file_ext_tolower' => TRUE
);