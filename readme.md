# Test task for calculating the commission using sample input file and several API's

## Notes before running the app:

1. The `https://api.exchangeratesapi.io/latest` URL is not working anymore. It is now available at `https://api.apilayer.com/exchangerates_data/latest`
2. IMPORTANT: `https://lookup.binlist.net/` introduced a rate limiter. I was able to get the country for three consecutive BIN's but afterward I had to wait for several minutes. Hence, I cut the sample input file to three lines, because adding more lines causes the app to fail due to the `429 Too Many Requests` response from the BinList API.
3. There are no unit tests.

## To launch the app
1. Copy `.env.example` into `.env`
2. Add your own APILayer API key
3. Run `php index.php <input_file>`
