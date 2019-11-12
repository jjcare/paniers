USE shalom_cnd;
SET character_set_client = utf8;
TRUNCATE TABLE families;
INSERT INTO families (`famno`, `nip`, `number`, `street`, `appt`, `code`, `tel_h`, `tel_alt`, `note`, `montant`, `foyer`, `location`) VALUES (01,71,"2810","Barclay","1","H3S 1J6","514-297-5457","514-651-9198","","100", "101", "45.510076,-73.62832139999999"),
(02,72,"3225","Barclay","6","H3S 1K2","514-344-9448","438-992-7907","","70", "102", "45.50603659999999,-73.6329643"),
(03,73,"4180","Barclay","8","H3S 1K8","514-224-6607","514-576-0457","","90", "103", "45.49929969999999,-73.6383515"),
(04,77,"4181","Barclay","1","H3S 1K9","5-147-596-9597","","","80", "104", "45.4994535,-73.6387008"),
(05,74,"4421","Barclay","7","H3S 1K9","514-449-6205","514-575-4849","","80", "105", "45.4987965,-73.63932779999999"),
(06,69,"4740","Barclay","7","H3W 1C7","438-383-4269","438-396-6731","","90", "106", "45.4967863,-73.6406101"),
(07,78,"4750","Barclay","5","H3W 1C7","514-731-1822","514-297-1822","","80", "107", "45.4965687,-73.64056699999999"),
(08,75,"4811","Barclay","204","H3W 0A5","514-733-3692","514-586-6483","","100", "108", "45.4959435,-73.64175519999999"),
(09,79,"5500","Pl. Beanminster","null","H3W 2M3","514-662-3789","514-357-4516","","70", "109", "45.493266,-73.630927"),
(10,82,"2901","Bedford","3","H3S 1G3","514-501-7200","514-341-6849","","80", "110", "45.5102598,-73.6317633"),
(11,80,"3165","Bedford","20","H3S 1G3","514-731-9930","438-872-4345","","80", "111", "45.50748369999999,-73.6343035"),
(12,81,"3330","Bedford","19","H3S 1G7","514-735-6095","514-686-5434","","90", "112", "45.5058731,-73.6351222"),
(13,90,"3580","Bedford","22","H3S 1G7","514-315-2547","438-923-5717","","80", "201", "45.5040807,-73.63690609999999"),
(14,88,"4200","Bouchette","21","H3S 1J2","514-589-7041","514-733-7692","","80", "202", "45.4988559,-73.63983569999999"),
(15,83,"4255","Bourret","411","H3S 1X1","514-357-5565","438-887-8185","","70", "203", "45.49613979999999,-73.6322993"),
(16,84,"4635","Bourret","49","H3W 1K9","438-288-6666","438-989-5961","","80", "204", "45.4946202,-73.6337058"),
(17,89,"4700","Bourret","202","H3W 1K8","514-294-3757","514-686-6586","","90", "205", "45.4935979,-73.63413419999999"),
(18,76,"4700","Bourret","302","H3W 1K8","514-739-7472","514-679-0031","","70", "206", "45.4935979,-73.63413419999999"),
(19,91,"4705","Bourret","107","H3W 1K9","514-969-0730","514-731-0730","","70", "207", "45.4939309,-73.63426129999999"),
(20,85,"4855","Bourret","106","H3W 1L2","514-225-3784","514-242-8374","","80", "208", "45.4924678,-73.6356485"),
(21,92,"4955","Buchan","113","H4P 1S4","514-900-2507","514-916-1274","","90", "209", "45.4981426,-73.6504159"),
(22,86,"5215","Côte-Ste-Catherine","12","H3W 1M9","514-243-9039","514-321-4556","","80", "210", "45.48848659999999,-73.6366985"),
(23,134,"2715","Pl. Darlington","305","H3S 1L4","514-775-5645","438-395-2492","","70", "211", "45.5112028,-73.62681169999999"),
(24,95,"2760","Pl. Darlington","14","H3S 1L5","514-317-1509","514-545-9661","","100", "212", "45.51006,-73.6265619"),
(25,96,"2820","Pl. Darlington","21","H3S 1L5","514-448-6769","514-813-0475","","80", "213", "45.509185,-73.627354"),
(26,93,"5755","Darlington","2","H3S 2H6","514-418-0872","438-922-8218","","70", "301", "45.5063054,-73.62256119999999"),
(27,94,"6815","Darlington","11","H3S 2J9","514-733-4620","438-862-1444","","70", "302", "45.5106068,-73.6313522"),
(28,98,"5566","Décelles","2","H3T 1W5","514-735-8587","514-344-5195","","80", "303", "45.4993331,-73.6237642"),
(29,97,"5795","Décelles","203","H3S 2C4","514-623-7990","514-969-5844","","70", "304", "45.501544,-73.6270925"),
(30,99,"6655","Décelles","31","H3S 2E6","438-875-8704","438-875-0530","","70", "305", "45.5058719,-73.63381009999999"),
(31,100,"4375","de Courtrai","104","H3S 1B8","514-452-2218","514-465-2731","","90", "306", "45.5000335,-73.643481"),
(32,101,"4851","de Courtrai","1","H3W 0A2","438-998-5642","514-577-8450","","80", "307", "45.4966715,-73.6464194"),
(33,87,"4851","de Courtrai","201","H3W 0A2","514-898-2852","514-629-8259","","70", "308", "45.4966715,-73.6464194"),
(34,102,"4785","De la Peltrie","201","H3W 1K4","514-735-0378","514-823-8182","","70", "309", "45.4939334,-73.63558750000001"),
(35,104,"4750","Dupuis","306","H3W 1N3","514-726-4842","438-998-2408","","70", "310", "45.4921143,-73.6309208"),
(36,63,"3721","Dupuis","24","H3T 1E5","514-567-2096","514-586-2501","","70", "401a", "45.497743,-73.6270751"),
(37,105,"3825","Dupuis","8","H3T 1E5","514-344-5195","438-777-7106","","100", "401b", "45.4960716,-73.62849659999999"),
(38,68,"3825","Dupuis","17","H3T 1E5","514-244-1728","514-344-7236","","70", "402a", "45.4960716,-73.62849659999999"),
(39,65,"3955","Dupuis","06","H3T 1E7","514-995-5597","514-357-4750","","80", "402b", "45.495856,-73.62886069999999"),
(40,106,"4660","Dupuis","6","H3W 1N3","438-990-2472","438-922-7119","","70", "403a", "45.4929968,-73.6306566"),
(41,107,"4980","Édouard-Monpetit","6","H3W 1P9","514-224-5019","438-889-9601","","80", "403b", "45.488132,-73.63226759999999"),
(42,103,"2650","Goyer","9","H3S 1H3","438-876-3703","514-623-0573","","90", "404a", "45.51215939999999,-73.62820500000001"),
(43,109,"2835","Goyer","1","H3S 1H2","514-360-2040","514-448-6769","","80", "404b", "45.5105594,-73.6301051"),
(44,110,"3080","Goyer","6","H3S 1H5","438-992-9794","438-979-9894","","80", "405a", "45.50812,-73.631584"),
(45,112,"3080","Goyer","9","H3S 1H5","514-733-8151","438-939-1305","","80", "405b", "45.50812,-73.631584"),
(46,108,"3280","Goyer","205","H3S 1J1","438-358-7829","438-368-7829","","80", "406a", "45.50526,-73.634456"),
(47,111,"3295","Goyer","3","H3S 1H9","514-564-1401","438-985-5056","","80", "406b", "45.5052175,-73.63512159999999"),
(48,113,"3345","Goyer","14","H3S 1H9","438-368-8943","514-561-0591","","90", "407a", "45.504602,-73.6356641"),
(49,114,"2835","Kent","10","H3S 1M8","514-544-6681","514-268-3095","","70", "407b", "45.50888519999999,-73.62713339999999"),
(50,115,"3450","Kent","null","H3S 1N2","514-898-2910","514-531-1237","","90", "408a", "45.5028715,-73.63143149999999"),
(51,116,"6920","Lemieux","23","H3W 2V9","514-345-5527","514-756-7402","","90", "408b", "45.4957982,-73.6465384"),
(52,117,"3885","Linton","3","H3S 1T3","438-402-3202","514-242-4055,438-228-1689","","80", "409a", "45.4988156,-73.6326617"),
(53,62,"3744","Mackenzie","null","H3S 1E5","438-877-9375","438-835-2246","","100", "409b", "45.502875,-73.63905"),
(54,118,"3780","Plamondon","10","H3S 1L9","438-495-9099","514-341-2220","","80", "501a", "45.5010785,-73.6355703"),
(55,120,"3780","Plamondon","11","H3S 1K9","438-931-3156","514-629-8314","","70", "501b", "45.5010785,-73.6355703"),
(56,121,"3780","Plamondon","14","H3S 1L9","438-875-8075","438-990-9727","","80", "502a", "45.5010785,-73.6355703"),
(57,64,"3795","Plamondon","2","H3S 1L8","514-621-8515","514-625-8515","","70", "502b", "45.5010827,-73.6359612"),
(58,119,"3905","Plamondon","9","H3S 1L8","514-507-5176","514-243-6936","","70", "503a", "45.5002469,-73.6366634"),
(59,122,"4605","Plamondon","3","H3W 1E4","514-814-9252","438-923-5671","","80", "503b", "45.49759400000001,-73.639057"),
(60,126,"4850","Plamondon","2","H3W 1E6","514-800-3383","514-880-7237","","70", "504a", "45.4947382,-73.64099709999999"),
(61,70,"4850","Plamondon","3","H3W 1E6","514-501-2050","438-937-8192","","90", "504b", "45.4947382,-73.64099709999999"),
(62,123,"4490","St-Kevin","2","H3T 1H9","514-771-4612","514-953-9830","","70", "505a", "45.4943707,-73.6279947"),
(63,124,"4620","St-Kevin","2","H3W 1N9","514-342-3923","514-812-7078","","70", "505b", "45.4930832,-73.62917870000001"),
(64,125,"4650","St-Kevin","15","H3W 1N9","514-573-1528","438-345-6162","","80", "506a", "45.49278760000001,-73.6293944"),
(65,127,"3110","Van Horne","null","H3S 1R4","514-344-8842","514-568-8593","","90", "506b", "45.5052744,-73.6282341"),
(66,128,"4765","Vezina","203","H3W 1B7","514-909-0214","514-733-6775","","80", "507a", "45.4972293,-73.6432579"),
(67,129,"4775","Vezina","203","H3W 1B7","514-731-3541","438-995-1832","","70", "507b", "45.4972011,-73.643269"),
(68,130,"4775","Vézina","205","H3W 1B7","514-735-2149","514-803-2149","","80", "508a", "45.4972011,-73.643269"),
(69,131,"4873","Vézina","15","H3W 1B9","514-739-7459","514-691-3410","","70", "508b", "45.4957331,-73.64468590000001"),
(70,67,"4873","Vézina","22","H3W 1B9","514-601-2649","514-923-2649","","70", "509a", "45.4957331,-73.64468590000001"),
(71,132,"4873","Vézina","26","H3W 1B9","514-735-5801","514-994-4360","","70", "509b", "45.4957331,-73.64468590000001"),
(72,66,"4891","Vézina","17","H3W 1B9","514-739-2330","514-232-6722","","70", "510a", "45.49571659999999,-73.64470519999999"),
(73,133,"4891","Vézina","32","H3W 1B9","438-725-5571","514-567-2096","","80", "510b", "45.49571659999999,-73.64470519999999");