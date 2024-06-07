<?php
// app/Swagger/StockPriceAnnotations.php

namespace App\Swagger;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Stock Price Tracker API",
 *     version="1.0.0"
 * )
 *
 * @OA\Post(
 *     path="/api/getStockPricesBySymbols",
 *     summary="Get stock prices by symbols",
 *     tags={"Stock Prices"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="symbols",
 *                 type="array",
 *                 @OA\Items(type="string"),
 *                 example={"AAPL", "GOOG", "MSFT"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\AdditionalProperties(
 *                 type="object",
 *                 @OA\Property(
 *                     property="symbol",
 *                     type="string",
 *                     description="Stock symbol",
 *                     example="AAPL"
 *                 ),
 *                 @OA\Property(
 *                     property="open",
 *                     type="number",
 *                     description="Opening price",
 *                     example=145.67
 *                 ),
 *                 @OA\Property(
 *                     property="close",
 *                     type="number",
 *                     description="Closing price",
 *                     example=146.78
 *                 ),
 *                 @OA\Property(
 *                     property="high",
 *                     type="number",
 *                     description="Highest price",
 *                     example=147.00
 *                 ),
 *                 @OA\Property(
 *                     property="low",
 *                     type="number",
 *                     description="Lowest price",
 *                     example=144.50
 *                 ),
 *                 @OA\Property(
 *                     property="volume",
 *                     type="number",
 *                     description="Volume of stocks traded",
 *                     example=1000000
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/getAllLatestStockPrices",
 *     summary="Get all latest stock prices",
 *     tags={"Stock Prices"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="array",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(
 *                     property="symbol",
 *                     type="string",
 *                     description="Stock symbol"
 *                 ),
 *                 @OA\Property(
 *                     property="price",
 *                     type="number",
 *                     description="Stock price"
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/getStockPricePolygon",
 *     summary="Stock Prices for a predifined stocks",
 *     tags={"Stock Prices"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\AdditionalProperties(
 *                 type="object",
 *                 @OA\Property(
 *                     property="symbol",
 *                     type="string",
 *                     description="Stock symbol",
 *                     example="AAPL"
 *                 ),
 *                 @OA\Property(
 *                     property="open",
 *                     type="number",
 *                     description="Opening price",
 *                     example=145.67
 *                 ),
 *                 @OA\Property(
 *                     property="close",
 *                     type="number",
 *                     description="Closing price",
 *                     example=146.78
 *                 ),
 *                 @OA\Property(
 *                     property="high",
 *                     type="number",
 *                     description="Highest price",
 *                     example=147.00
 *                 ),
 *                 @OA\Property(
 *                     property="low",
 *                     type="number",
 *                     description="Lowest price",
 *                     example=144.50
 *                 ),
 *                 @OA\Property(
 *                     property="volume",
 *                     type="number",
 *                     description="Volume of stocks traded",
 *                     example=1000000
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Bad request"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 *
 * @OA\Post(
 *     path="/api/insertStockPrices",
 *     summary="Insert stock data for a predifened set of stocks",
 *     tags={"Stock Prices"},
 *     @OA\Response(
 *         response=200,
 *         description="Successful insertion",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="symbol",
 *                 type="string",
 *                 description="Stock symbol",
 *                 example="AAPL"
 *             ),
 *             @OA\Property(
 *                 property="date",
 *                 type="string",
 *                 format="date",
 *                 description="Date of the stock prices",
 *                 example="2023-06-05"
 *             ),
 *             @OA\Property(
 *                 property="open",
 *                 type="number",
 *                 description="Opening price",
 *                 example=145.67
 *             ),
 *             @OA\Property(
 *                 property="close",
 *                 type="number",
 *                 description="Closing price",
 *                 example=146.78
 *             ),
 *             @OA\Property(
 *                 property="high",
 *                 type="number",
 *                 description="Highest price",
 *                 example=147.00
 *             ),
 *             @OA\Property(
 *                 property="low",
 *                 type="number",
 *                 description="Lowest price",
 *                 example=144.50
 *             ),
 *             @OA\Property(
 *                 property="volume",
 *                 type="number",
 *                 description="Volume of stocks traded",
 *                 example=1000000
 *             ),
 *             @OA\Property(
 *                 property="after_hours",
 *                 type="number",
 *                 description="After hours price",
 *                 example=148.00
 *             ),
 *             @OA\Property(
 *                 property="pre_market",
 *                 type="number",
 *                 description="Pre market price",
 *                 example=143.00
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=429,
 *         description="API rate limit exceeded"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 *
 * @OA\Get(
 *     path="/api/stock-prices/{symbol}/{date}",
 *     summary="Get stock prices by symbol and date",
 *     tags={"Stock Prices"},
 *     @OA\Parameter(
 *         name="symbol",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             example="AAPL"
 *         ),
 *         description="Stock symbol"
 *     ),
 *     @OA\Parameter(
 *         name="date",
 *         in="path",
 *         required=true,
 *         @OA\Schema(
 *             type="string",
 *             format="date",
 *             example="2023-06-05"
 *         ),
 *         description="Date for which to fetch the stock prices"
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="symbol",
 *                 type="string",
 *                 description="Stock symbol",
 *                 example="AAPL"
 *             ),
 *             @OA\Property(
 *                 property="date",
 *                 type="string",
 *                 format="date",
 *                 description="Date of the stock prices",
 *                 example="2023-06-05"
 *             ),
 *             @OA\Property(
 *                 property="open",
 *                 type="number",
 *                 description="Opening price",
 *                 example=145.67
 *             ),
 *             @OA\Property(
 *                 property="close",
 *                 type="number",
 *                 description="Closing price",
 *                 example=146.78
 *             ),
 *             @OA\Property(
 *                 property="high",
 *                 type="number",
 *                 description="Highest price",
 *                 example=147.00
 *             ),
 *             @OA\Property(
 *                 property="low",
 *                 type="number",
 *                 description="Lowest price",
 *                 example=144.50
 *             ),
 *             @OA\Property(
 *                 property="volume",
 *                 type="number",
 *                 description="Volume of stocks traded",
 *                 example=1000000
 *             ),
 *             @OA\Property(
 *                 property="after_hours",
 *                 type="number",
 *                 description="After hours price",
 *                 example=148.00
 *             ),
 *             @OA\Property(
 *                 property="pre_market",
 *                 type="number",
 *                 description="Pre market price",
 *                 example=143.00
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Stock symbol or data not found",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="message",
 *                 type="string",
 *                 example="Stock symbol not found"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 * * @OA\Post(
 *     path="/api/getPriceChange",
 *     summary="Calculate price change for given symbols between two dates",
 *     tags={"Stock Prices"},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="symbols",
 *                 type="array",
 *                 @OA\Items(type="string"),
 *                 example={"AAPL", "GOOG", "MSFT"}
 *             ),
 *             @OA\Property(
 *                 property="start_date",
 *                 type="string",
 *                 format="date",
 *                 example="2023-01-01"
 *             ),
 *             @OA\Property(
 *                 property="end_date",
 *                 type="string",
 *                 format="date",
 *                 example="2023-01-31"
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Successful response",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\AdditionalProperties(
 *                 type="number",
 *                 description="Price change percentage",
 *                 example=0.05
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=400,
 *         description="Invalid input parameters"
 *     ),
 *     @OA\Response(
 *         response=500,
 *         description="Internal server error"
 *     )
 * )
 */
class StockPriceAnnotations
{
}
