sc.utils = {};

/* @description
 *      calculates the remaining time left after something hapenened from start
 *      if you want a minimum time to take the whole action
 *      So minTime - (now - start);
 * @param {Date} startTime
 * @param {number} minTime minimum time in milliseconds
 * @returns {number} remaining time in milliseconds, but never less then 0
 */
sc.utils.remainingTime = function (startTime, minTime) {
    var start = startTime || new Date(),
        now = new Date(),
        diff = now - start,
        remaining = minTime - diff < 0 ? 0 : minTime - diff;
    return remaining;
};


