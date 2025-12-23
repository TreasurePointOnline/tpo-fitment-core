import time
import pandas as pd
from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.common.exceptions import TimeoutException, NoSuchElementException

# --- CONFIG ---
BASE_URL = "https://www.ogaudio.com"
CATEGORY_URLS = {
    "Amplifiers": "/collections/amplifiers",
    "Subwoofers": "/collections/subwoofers",
    "Audio Speakers": "/collections/speakers",
    "Empty Enclosures": "/collections/boxes-and-enclosures",
    "Amp Kits & Accessories": "/collections/accessories", # Assuming this is the correct URL for accessories
}
OUTPUT_FILE = "og_audio_full_catalog.csv"

def scrape_og_full_catalog():
    print("🤖 Launching Chrome Bot for Full Catalog Scraping...")
    
    options = webdriver.ChromeOptions()
    options.add_argument('--start-maximized')
    options.add_argument('--disable-blink-features=AutomationControlled')
    # options.add_argument('--headless') # Run headless to not open browser window, useful for automation
    
    driver = webdriver.Chrome(service=Service(ChromeDriverManager().install()), options=options)
    all_products_data = []

    try:
        for category_name, category_path in CATEGORY_URLS.items():
            full_url = BASE_URL + category_path
            print(f"\n📡 Navigating to {category_name}: {full_url}...")
            driver.get(full_url)
            time.sleep(3) # Let initial page load

            # Scroll to load all dynamic content
            print(f"   Scrolling to load all products in {category_name}...")
            last_height = driver.execute_script("return document.body.scrollHeight")
            for i in range(5): # Adjust scroll attempts based on page length
                driver.execute_script("window.scrollTo(0, document.body.scrollHeight);")
                time.sleep(2)
                new_height = driver.execute_script("return document.body.scrollHeight")
                if new_height == last_height:
                    break # Reached bottom
                last_height = new_height
            
            print(f"   Extracting product data for {category_name}...")
            
            # Use more robust CSS selectors based on common Shopify/e-commerce patterns
            # Look for product cards/items
            product_elements = driver.find_elements(By.CSS_SELECTOR, 
                ".product-item, .grid__item, .product-card, .card-wrapper, .collection-product"
            )

            if not product_elements:
                print(f"   ⚠️ No product elements found for {category_name} with generic selectors. Trying broader link search.")
                # Fallback: Find links that go to product pages
                product_links = driver.find_elements(By.CSS_SELECTOR, "a[href*='/products/']")
                # Filter out duplicates and non-product links
                product_urls = set()
                for link in product_links:
                    href = link.get_attribute('href')
                    if href and '/products/' in href and BASE_URL in href and '?' not in href and '#' not in href:
                        product_urls.add(href)
                
                print(f"   Found {len(product_urls)} product links. Visiting each one...")
                for p_url in list(product_urls)[:20]: # Limit for testing
                    try:
                        driver.get(p_url)
                        WebDriverWait(driver, 10).until(EC.presence_of_element_located((By.TAG_NAME, 'h1')))
                        
                        title_elem = driver.find_element(By.TAG_NAME, 'h1')
                        title = title_elem.text.strip()
                        
                        price_elem = driver.element = driver.find_element(By.CSS_SELECTOR, '.price, [data-product-price]')
                        price_text = price_elem.text.strip().replace('$', '').replace('USD', '').replace(',', '')
                        price = float(price_text) if price_text else 0.00

                        sku_elem = driver.find_element(By.CSS_SELECTOR, '[data-product-sku], .product-single__sku')
                        sku = sku_elem.text.strip() if sku_elem else title
                        
                        all_products_data.append({
                            'Name': title,
                            'SKU': sku,
                            'Price': price,
                            'Category': category_name,
                            'URL': p_url
                        })
                        print(f"   + Captured: {title} [${price}] ({sku})")
                    except (NoSuchElementException, TimeoutException):
                        print(f"     Failed to scrape individual product page: {p_url}")
                    time.sleep(1) # Be nice to the server
                continue # Move to next category

            for product_elem in product_elements:
                try:
                    # Title
                    title_elem = product_elem.find_element(By.CSS_SELECTOR, '.product-title, .h4, [data-product-title]')
                    title = title_elem.text.strip()
                    
                    # Price
                    price_elem = product_elem.find_element(By.CSS_SELECTOR, '.price, [data-product-price]')
                    price_text = price_elem.text.strip().replace('$', '').replace('USD', '').replace(',', '')
                    price = float(price_text) if price_text else 0.00
                    
                    # SKU (often derived from title or a specific element)
                    # For OG Audio, names are often SKUs
                    sku = title # Assuming title is SKU for now

                    all_products_data.append({
                        'Name': title,
                        'SKU': sku,
                        'Price': price,
                        'Category': category_name,
                        'URL': product_elem.find_element(By.TAG_NAME, 'a').get_attribute('href')
                    })
                    print(f"   + Captured: {title} [${price}] ({sku})")
                except NoSuchElementException:
                    continue # Skip if essential elements are missing
                except Exception as e:
                    print(f"     Error processing product: {e}")
                    continue

    except Exception as e:
        print(f"❌ Critical Error during scraping: {e}")
    finally:
        driver.quit()

    # Save to CSV
    if all_products_data:
        df = pd.DataFrame(all_products_data)
        df.to_csv(OUTPUT_FILE, index=False)
        print(f"\n✅ SUCCESS! Scraped {len(all_products_data)} products.")
        print(f"📂 Saved to: {OUTPUT_FILE}")
    else:
        print("\n⚠️ Scraping finished but found 0 products across all categories.")

if __name__ == "__main__":
    scrape_og_full_catalog()
