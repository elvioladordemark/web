const puppeteer = require('puppeteer');

module.exports = async (req, res) => {
    const browser = await puppeteer.launch({
        args: ['--no-sandbox', '--disable-setuid-sandbox'],
    });
    const page = await browser.newPage();

    // Navegar al enlace del reproductor
    await page.goto('https://pcmirror.cc/tv/play/81220014/', {
        waitUntil: 'networkidle2',
    });

    // Interceptar las peticiones para capturar el .m3u8
    let m3u8Url = null;
    page.on('response', async (response) => {
        const url = response.url();
        if (url.includes('.m3u8?in=')) {
            m3u8Url = url;
        }
    });

    // Esperar un tiempo suficiente para que se generen los enlaces
    await page.waitForTimeout(5000);

    await browser.close();

    if (m3u8Url) {
        // Devuelve el enlace como respuesta
        res.setHeader('Content-Type', 'application/vnd.apple.mpegurl');
        res.status(200).send(`#EXTM3U\n${m3u8Url}`);
    } else {
        res.status(404).send('No se encontró un enlace .m3u8.');
    }
};
