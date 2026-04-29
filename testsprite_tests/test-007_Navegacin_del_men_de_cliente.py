import asyncio
from playwright import async_api
from playwright.async_api import expect

async def run_test():
    pw = None
    browser = None
    context = None

    try:
        # Start a Playwright session in asynchronous mode
        pw = await async_api.async_playwright().start()

        # Launch a Chromium browser in headless mode with custom arguments
        browser = await pw.chromium.launch(
            headless=True,
            args=[
                "--window-size=1280,720",         # Set the browser window size
                "--disable-dev-shm-usage",        # Avoid using /dev/shm which can cause issues in containers
                "--ipc=host",                     # Use host-level IPC for better stability
                "--single-process"                # Run the browser in a single process mode
            ],
        )

        # Create a new browser context (like an incognito window)
        context = await browser.new_context()
        context.set_default_timeout(5000)

        # Open a new page in the browser context
        page = await context.new_page()

        # Interact with the page elements to simulate user flow
        # -> Navigate to http://localhost:8081/
        await page.goto("http://localhost:8081/")
        
        # -> Navegar a http://localhost:8081/?url=menu&mesa=1 para ver el menú de productos y proceder con las verificaciones.
        await page.goto("http://localhost:8081/?url=menu&mesa=1")
        
        # -> Click the 'Agregar' button for the first product (Inca Kola, index 64), wait for the UI to update, then extract the category headings and the cart badge text to verify the cart updated.
        frame = context.pages[-1]
        # Click element
        elem = frame.locator('xpath=/html/body/main/section/div/div/button').nth(0)
        await asyncio.sleep(3); await elem.click()
        
        # --> Assertions to verify final state
        frame = context.pages[-1]
        assert await frame.locator("xpath=//*[contains(., 'Bebidas')]").nth(0).is_visible(), "El menú debería mostrar la categoría Bebidas con productos agrupados.",
        assert await frame.locator("xpath=//*[contains(., 'Agregar')]").nth(0).is_visible(), "Cada producto debería mostrar un botón 'Agregar' para añadirlo al carrito.",
        assert await frame.locator("xpath=//*[contains(., 'Inca Kola')]").nth(0).is_visible(), "El carrito debería actualizarse y mostrar Inca Kola después de agregar el producto."]}
        await asyncio.sleep(5)

    finally:
        if context:
            await context.close()
        if browser:
            await browser.close()
        if pw:
            await pw.stop()

asyncio.run(run_test())
    