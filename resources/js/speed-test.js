let testImage = '/images/transparent.png'; // small image in your server
let images = [];

export default async function speedTest(timesToTest = 5) {
    const tThreshold = 150; //ms

    return new Promise((resolve) => {

        for (let i = 0; i < timesToTest - 1; i++) {
            images.push(testImage);
        }

        loadImages(images).then(({images, times}) => {
            let sum = times.reduce((a, b) => {
                return a + b;
            });
            let avg = (sum / times.length);
            const isConnectedFast = (avg <= tThreshold);

            console.log('Time: ' + (avg.toFixed(2)) + 'ms - isConnectedFast? ' + isConnectedFast);

            resolve({images, avg, sum, isConnectedFast});
        });
    });
}

async function loadImages(imageUrlArray) {
    const promiseArray = []; // create an array for promises
    const images = []; // array for the images
    const times = [];

    for (let imageUrl of imageUrlArray) {

        promiseArray.push(new Promise(resolve => {
            let tStart = new Date().getTime();

            const img = new Image();
            // if you don't need to do anything when the image loads,
            // then you can just write img.onload = resolve;

            img.onload = function () {
                let tEnd = new Date().getTime();
                let tTimeTook = tEnd - tStart;
                times.push(tTimeTook);

                // resolve the promise, indicating that the image has been loaded
                resolve();
            };

            img.src = imageUrl + '?t=' + tStart + (Math.random() * 10000);
            images.push(img);
        }));
    }

    await Promise.all(promiseArray); // wait for all the images to be loaded
    return {images, times};
}
