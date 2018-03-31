/* -----------------------------------------------------------------------------

  CalenStyle - Responsive Event Calendar
  Version 2.0.8
  Copyright (c)2017 Lajpat Shah
  Contributors : https://github.com/nehakadam/CalenStyle/contributors
  Repository : https://github.com/nehakadam/CalenStyle
  Homepage : https://nehakadam.github.io/CalenStyle

 ----------------------------------------------------------------------------- */

/*

	language: English
	file: CalenStyle-i18n-en

*/

(function ($) {
    $.CalenStyle.i18n["no"] = $.extend($.CalenStyle.i18n["no"], {
        veryShortDayNames: "So_Ma_Ti_On_To_Fr_Lø".split("_"),
		shortDayNames: "Søn_Man_Tir_Ons_Tor_Fre_Lør".split("_"),
		fullDayNames: "Søndag_Mandag_Tirsdag_Onsdag_Torsdag_Fredag_Lørdag".split("_"),
		shortMonthNames: "Jan_Feb_Mar_Apr_Mai_Jun_Jul_Aug_Sep_Okt_Nov_Des".split("_"),
		fullMonthNames: "Januar_Februar_Mars_April_Mai_Juni_Juli_August_September_Oktober_November_Desember".split("_"),
		numbers: "0_1_2_3_4_5_6_7_8_9".split("_"),
		eventTooltipContent: "Default",
		formatDates: {},
		miscStrings: {
			today: "I dag",
			week: "Uke",
			allDay: "Hele dagen",
			ends: "Slutt"
		},
		duration: "Default",
		durationStrings: {
			y: ["år ", "år "],
			M: ["måned ", "måneder "],
			w: ["uke ", "uker "],
			d: ["dag ", "dager "],
			h: ["time ", "timer "],
			m: ["minutt ", "minutter "],
			s: ["sekund ", "sekunder "]
		},
		viewDisplayNames: {
			DetailedMonthView: "Måned",
			MonthView: "Måned",
			WeekView: "Uke",
			DayView: "Dag",
			AgendaView: "Dagsorden"
		}
    });
})(jQuery);