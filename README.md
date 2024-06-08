# Stock Prices API Documentation

## Table of Contents
1. [Introduction](#introduction)
2. [Environment Configuration](#environment-configuration)
3. [Application Structure](#application-structure)
4. [Routes](#routes)
5. [Services](#services)
6. [Models](#models)
7. [How to Use the API](#how-to-use-the-api)
8. [Custom Commands](#custom-commands)

## Introduction
The Stock Prices API is built using Laravel and provides endpoints to fetch, insert, and analyze stock prices using the Alpha Vantage and Polygon services.

## Environment Configuration
Before running the application, ensure your `.env` file is configured correctly:

```env
APP_NAME=StockPricesAPI
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_URL=http://your-server-ip

DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-username
DB_PASSWORD=your-database-password

ALPHA_VANTAGE_API_KEY=your-alpha-vantage-api-key
POLYGON_API_KEY=your-polygon-api-key


Application Structure
The key components of the application include:

Controllers: Handle the HTTP requests and responses.
Services: Contain the business logic for interacting with external APIs.
Models: Represent the database structure.
Routes
Route: /api/stock-prices
Method: GET
Description: Fetch stock prices from the Polygon API.
Response: JSON object with stock prices.
Route: /api/insert-stock-prices
Method: POST
Description: Insert stock prices into the database.
Response: JSON message indicating success or failure.
Route: /api/get-stock-prices-by-symbols
Method: POST
Description: Get stock prices for specific symbols.
Parameters: symbols (array of stock symbols)
Response: JSON object with stock prices.
Route: /api/get-price-change
Method: POST
Description: Get price changes for stocks between two dates.
Parameters: symbols (array of stock symbols), start_date, end_date
Response: JSON object with price changes.
Route: /api/get-stock-prices-by-date/{symbol}/{date}
Method: GET
Description: Get stock prices for a specific symbol and date.
Parameters: symbol (stock symbol), date (date in YYYY-MM-DD format)
Response: JSON object with stock price details.
Services
AlphaVantageService
A service to interact with the Alpha Vantage API to fetch stock prices.

Methods
getStockPrices(): Fetches stock prices for a predefined set of stocks.
PolygonService
A service to interact with the Polygon API to fetch and manage stock prices.

Methods
getStockPrices(): Fetches current stock prices.
insertStockPrices(): Inserts the fetched stock prices into the database.
getAllLatestStockPrices(): Retrieves the latest stock prices from the database.
getStockPricesBySymbols(array $symbols): Fetches stock prices for specified symbols.
calculatePriceChange(array $symbols, string $startDate, string $endDate): Calculates price changes between two dates.
Models
Stock
Represents the stock entity in the database.

StockMarketPrice
Represents the stock market price entity in the database.

How to Use the API
Fetch Stock Prices
To fetch stock prices, make a GET request to /api/stock-prices:

curl -X GET http://your-server-ip/api/stock-prices

Insert Stock Prices
To insert stock prices, make a POST request to /api/insert-stock-prices:

curl -X POST http://your-server-ip/api/insert-stock-prices
Get Stock Prices by Symbols
To get stock prices for specific symbols, make a POST request to /api/get-stock-prices-by-symbols with a JSON payload:

curl -X POST -H "Content-Type: application/json" -d '{"symbols": ["AAPL", "MSFT"]}' http://your-server-ip/api/get-stock-prices-by-symbols
Get Price Change
To get the price change for stocks between two dates, make a POST request to /api/get-price-change with a JSON payload:

curl -X POST -H "Content-Type: application/json" -d '{"symbols": ["AAPL", "MSFT"], "start_date": "2023-01-01", "end_date": "2023-06-01"}' http://your-server-ip/api/get-price-change
Get Stock Prices by Date
To get stock prices for a specific symbol and date, make a GET request to /api/get-stock-prices-by-date/{symbol}/{date}:

curl -X GET http://your-server-ip/api/get-stock-prices-by-date/AAPL/2023-01-01

## Custom Commands

### fetchStockPrices Command

The `fetchStockPrices` command is a custom Laravel command used to fetch and process stock prices. This command is useful for automating the retrieval of stock data from the configured APIs.

#### Command Definition

The command is defined in the `App\Console\Commands\FetchStockPrices` class. This class extends the `Command` class provided by Laravel.

#### Usage

To run the `fetchStockPrices` command, use the following Artisan command:

```bash
php artisan fetchStockPrices