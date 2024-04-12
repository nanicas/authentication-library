## Configurar o client e secret no .env
```
NANICAS_CLIENT_ID=<int>
NANICAS_CLIENT_SECRET=<secret>
NANICAS_AUTHORIZATION_API_URL=auth-app:8000/
```

## Adicionar os providers em config/app.php
```
'providers' => [
    \Nanicas\Auth\Frameworks\Laravel\Providers\AppServiceProvider::class,
    \Nanicas\Auth\Frameworks\Laravel\Providers\BootstrapServiceProvider::class,
    \Nanicas\Auth\Frameworks\Laravel\Providers\AuthServiceProvider::class,
],
```

## Alterar os guards e providers em `config/auth.php`
```
'guards' => [
    'web' => [
        'driver' => 'custom_session',
        'provider' => 'custom',
    ],
],
```
```
'providers' => [
    'custom' => [
        'driver' => 'custom_session',
        'model' => App\Models\User::class,
    ],
],
```

## Adicionar um apelido (alias) para o middleware em `app/Http/Kernel.php`
```
'auth.nanicas' => \Nanicas\Auth\Frameworks\Laravel\Http\Middleware\Authenticate::class,
```

## Executar o comando de publicação dos arquivos de configuração
`php artisan vendor:publish --tag="nanicas_auth:config"`

Após o comando, favor verificar no diretório `config` (raiz) se os arquivos foram transferidos:
- `nanicas_authorization.php`