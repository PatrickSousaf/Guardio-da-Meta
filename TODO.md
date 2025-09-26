# Plano de Correção de Erros no Projeto Guardião da Meta

## Passos Pendentes
- [ ] 1. Corrigir Conexão com Banco de Dados: Atualizar .env manualmente e iniciar MySQL no XAMPP.
- [ ] 2. Instalar Dependências: Executar `composer install`.
- [ ] 3. Publicar Views do AdminLTE: Executar `php artisan vendor:publish --provider="JeroenNoten\LaravelAdminLte\ServiceProvider" --tag=adminlte-views`.
- [ ] 4. Gerar Chave da App e Executar Migrações: Executar `php artisan key:generate` e `php artisan migrate`.
- [ ] 5. Semeiar Banco de Dados (opcional): Executar `php artisan db:seed`.
- [ ] 6. Compilar Assets do Frontend: Executar `npm install` e `npm run build`.
- [ ] 7. Limpar Caches: Executar `php artisan config:clear && php artisan view:clear && php artisan cache:clear && php artisan route:clear`.
- [ ] 8. Iniciar Servidor e Testar: Executar `php artisan serve` e acessar http://127.0.0.1:8000/register.

## Notas
- Credenciais MySQL: Confirme DB_USERNAME=root, DB_PASSWORD='' (padrão XAMPP), DB_DATABASE=guardiao_da_meta.
- Atualize este arquivo conforme os passos forem concluídos.
