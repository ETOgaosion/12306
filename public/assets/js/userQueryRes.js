$('input').filter(
    function () {
        return this.id.match(/seat-type-(\d*)-(\d*)-check/);
    }
).change(
    function (){
        if ($(this).is(':checked')) {
            $(this).siblings().prop('disabled', false);
        }
        else {
            $(this).siblings().prop('disabled', true);
        }
    }
)

// function switchSeatToHardSeat(){
//     for (let i = 0; i < 7; i++)
//     {
//         if (i === 0) {
//             $('#seat-type-' + i.toString()).hide();
//         }
//     }
//     $('#seat-type-0').show();
// }
//
// function switchSeatToSoftSeat(){
//     console.log("soft click");
//     for (let i = 0; i < 7; i++)
//     {
//         if (i === 1) {
//             $('[id="seat-type-' + i.toString() + '"]').hide();
//         }
//     }
//     $('[id="seat-type-1"]').show();
// }
//
// function switchSeatToHardBedTop(){
//     for (let i = 0; i < 7; i++)
//     {
//         if (i === 2) {
//             $('[id="seat-type-' + i.toString() + '"]').hide();
//         }
//     }
//     $('[id="seat-type-2"]').show();
// }
//
// function switchSeatToHardBedMid(){
//     for (let i = 0; i < 7; i++)
//     {
//         if (i === 3) {
//             $('[id="seat-type-' + i.toString() + '"]').hide();
//         }
//     }
//     $('[id="seat-type-3"]').show();
// }
//
// function switchSeatToHardBedDown(){
//     for (let i = 0; i < 7; i++)
//     {
//         if (i === 4) {
//             $('[id="seat-type-' + i.toString() + '"]').hide();
//         }
//     }
//     $('[id="seat-type-4"]').show();
// }
//
// function switchSeatToSoftBedTop(){
//     for (let i = 0; i < 7; i++)
//     {
//         if (i === 5) {
//             $('[id="seat-type-' + i.toString() + '"]').hide();
//         }
//     }
//     $('[id="seat-type-5"]').show();
// }
//
// function switchSeatToSoftBedDown(){
//     for (let i = 0; i < 7; i++)
//     {
//         if (i === 6) {
//             $('[id="seat-type-' + i.toString() + '"]').hide();
//         }
//     }
//     $('[id="seat-type-6"]').show();
// }
//
// var btn = document.getElementById('hardSeatDpItem');
// btn.onclick = switchSeatToHardSeat;
// btn = document.getElementById('softSeatDpItem');
// btn.onclick = switchSeatToSoftSeat;
// btn = document.getElementById('hardBedTopDpItem');
// btn.onclick = switchSeatToHardBedTop;
// btn = document.getElementById('hardBedMidDpItem');
// btn.onclick = switchSeatToHardBedMid;
// btn = document.getElementById('hardBedDownDpItem');
// btn.onclick = switchSeatToHardBedDown;
// btn = document.getElementById('softBedTopDpItem');
// btn.onclick = switchSeatToSoftBedTop;
// btn = document.getElementById('softBedDownDpItem');
// btn.onclick = switchSeatToSoftBedDown;
//
// window.onload = function () {
//     console.log('enter window');
//     $('#hardSeatDpItem').onclick = switchSeatToHardSeat;
//     $('#softSeatDpItem').onclick = switchSeatToSoftSeat;
//     $('#hardBedTopDpItem').onclick = switchSeatToHardBedTop;
//     $('#hardBedMidDpItem').onclick = switchSeatToHardBedMid;
//     $('#hardBedDownDpItem').onclick = switchSeatToHardBedDown;
//     $('#softBedTopDpItem').onclick = switchSeatToSoftBedTop;
//     $('#softBedDownDpItem').onclick = switchSeatToSoftBedDown;
// }