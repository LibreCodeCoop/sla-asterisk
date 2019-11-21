# Dashboard para monitoramento de callcenter

## Pré-requisistos para utilizar ambiente com Docker

* Docker
* docker-compose

## Instalação utilizando docker

OBS: Lembre de editar o arquivo `.env` e colocar os dados de seu ambiente

OBS2: Caso queira rodar a aplicação ou banco de dados em alguma porta diferente das padrões, edite o arquivo `docker-compose.yml`

```bash
git clone https://github.com/lyseontech/sla-asterisk
cd sla-asterisk
cp .env.example .env
docker-compose build
docker-compose up
```
Após levantar a aplicação, execute o script de seeds para popular o banco com os dados de teste:

```bash
docker-compose exec php7 php vendor/bin/phinx seed:run
```

## Utilização

Acesse a aplicação no navegador informando o IP do servidor ou de sua máquina local.

### Configuração

Clique no botão de configuração para configurar os gráficos.

#### Window
Os dados serão coletados a cada minuto e inseridos em uma tabela de histórico. Window é o tamanho em segundos da janela deslizante para calcular a média do SLA medido antes de ser inserido no banco.


#### SLA

Valor máximo de SLA esperado para a métrica

#### Queue
Número da fila a ser monitorada

#### refresh
Intervalo em segundos que os gráficos serão atualizados na tela. Lembrando que os dados são inseridos no banco a cada minuto.

#### Métrica

Nome da métrica a ser monitorada.

### Tipos de gráficos
#### Gráfico donnut

Exibe o último valor inserido na tabela de histórico para os gráficos de TMA, TME e TMO e a informação em tempo real para o gráfico de Fila.

#### Gráfico de linha

Exibe o histórico das últimas 24h de medição para TMA, TME e TMO e da última hora para Fila.

### Relatórios

| Tipo | Local |
|:---:|:---:|
| BI | `/relatorio/index.php` |
| Abandono | `/relatorio_abandono.php` |