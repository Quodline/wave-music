export function formattedDuration(duration) {
    const minStr = Math.floor(duration / 60).toString();
    const secStr = Math.floor(duration % 60).toString();

    return minStr.padStart(2, '0') + ':' + secStr.padStart(2, '0');
}
