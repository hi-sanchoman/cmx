const puppeteer = require('puppeteer');
const process = require('process');

const sleep = (milliseconds) => {
  return new Promise(resolve => setTimeout(resolve, milliseconds))
}

const waitTillHTMLRendered = async (page, timeout = 30000) => {
  const checkDurationMsecs = 1000;
  const maxChecks = timeout / checkDurationMsecs;
  let lastHTMLSize = 0;
  let checkCounts = 1;
  let countStableSizeIterations = 0;
  const minStableSizeIterations = 3;

  while(checkCounts++ <= maxChecks){
    let html = await page.content();
    let currentHTMLSize = html.length; 

    let bodyHTMLSize = await page.evaluate(() => document.body.innerHTML.length);

    console.log('last: ', lastHTMLSize, ' <> curr: ', currentHTMLSize, " body html size: ", bodyHTMLSize);

    if(lastHTMLSize != 0 && currentHTMLSize == lastHTMLSize) 
      countStableSizeIterations++;
    else 
      countStableSizeIterations = 0; //reset the counter

    if(countStableSizeIterations >= minStableSizeIterations) {
      console.log("Page rendered fully..");
      break;
    }

    lastHTMLSize = currentHTMLSize;
    await page.waitFor(checkDurationMsecs);
  }  
};


async function run () {
//console.log(process.argv);
//return;

var args = process.argv.slice(2);
//console.log(args[0]);
//return;

  const browser = await puppeteer.launch({ headless: true, args: ['--no-sandbox'], executablePath: '/usr/bin/google-chrome'});
  const page = await browser.newPage();
  
var url = 'http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-cartogram/' + args[0] + '/' + args[1];
//console.log(url);
//return;

await page.setViewport({width: 700, height: 675});

await page.goto(url, {waitUntil: 'networkidle0'});
  //await sleep(10000)
 
  //await Promise.race([page.screenshot({path: './foo2.png'}), new Promise((resolve, reject) => setTimeout(reject, 5000))]);
  
  await waitTillHTMLRendered(page);
  await page.screenshot({path: '/var/www/vhosts/cemexlab.kz/httpdocs/public/img/map/cartograms/' + args[0] + '-' + args[1] + '.png'});

  browser.close();
}
run();
