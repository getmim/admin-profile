# admin-profile

## Instalasi

Jalankan perintah di bawah di folder aplikasi:

```
mim app install admin-profile
```

## Konfigurasi

Tambahkan konfigurasi seperti di bawah pada aplikasi/module untuk menambahkan sidebar
menu editor profile.

```php
return [
	'adminProfile' => [
		'sidebar' => [
			'/Label/' => ['perms', 'route']
		]
	]
];
```