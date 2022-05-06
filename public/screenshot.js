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

function waitForNetworkIdle(page, timeout, maxInflightRequests = 0) {
  page.on('request', onRequestStarted);
  page.on('requestfinished', onRequestFinished);
  page.on('requestfailed', onRequestFinished);

  let inflight = 0;
  let fulfill;
  let promise = new Promise(x => fulfill = x);
  let timeoutId = setTimeout(onTimeoutDone, timeout);
  return promise;

  function onTimeoutDone() {
    page.removeListener('request', onRequestStarted);
    page.removeListener('requestfinished', onRequestFinished);
    page.removeListener('requestfailed', onRequestFinished);
    fulfill();
  }

  function onRequestStarted() {
    ++inflight;
    if (inflight > maxInflightRequests)
      clearTimeout(timeoutId);
  }

  function onRequestFinished() {
    if (inflight === 0)
      return;
    --inflight;
    if (inflight === maxInflightRequests)
      timeoutId = setTimeout(onTimeoutDone, timeout);
  }
}


async function run () {
	//console.log(process.argv);
	//return;

	var args = process.argv.slice(2);
	//console.log(args[0]);
	//return;

  	const browser = await puppeteer.launch({ headless: true, args: ['--no-sandbox', '--disable-setuid-sandbox'], executablePath: '/usr/bin/google-chrome'});
  	const page = await browser.newPage();
  
	var url = 'http://185.146.3.112/plesk-site-preview/cemexlab.kz/https/185.146.3.112/show-cartogram/' + args[0] + '/' + args[1];
	//console.log(url);
	//return;

	await page.setViewport({width: 700, height: 675});
  	await page.setDefaultNavigationTimeout(0);
	//await page.goto(url, {waitUntil: 'load', timeout: 0});
  	//await sleep(10000)
 
  	//await Promise.race([page.screenshot({path: './foo2.png'}), new Promise((resolve, reject) => setTimeout(reject, 5000))]);
  
  	//await waitTillHTMLRendered(page);
  	await page.goto(url);
  	//await waitForNetworkIdle(page, 500, 0);
	//await page.waitForFunction('isLoaded');
	await page.waitForSelector('#isLoaded', {visible: true, timeout: 0});
	
	// make screenshot
	await page.screenshot({path: '/var/www/vhosts/cemexlab.kz/httpdocs/public/img/map/cartograms/' + args[0] + '-' + args[1] + '.png'});

  browser.close();
}
run();
