-- MySQL dump 10.13  Distrib 5.1.57, for apple-darwin10.7.0 (i386)
--
-- Host: localhost    Database: lart_weneed2
-- ------------------------------------------------------
-- Server version	5.1.57

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `image`
--

DROP TABLE IF EXISTS `image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thumb` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `image` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `image`
--

LOCK TABLES `image` WRITE;
/*!40000 ALTER TABLE `image` DISABLE KEYS */;
/*!40000 ALTER TABLE `image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `lang` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `subtitle` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (33,'WENÉE 薇妮體雕sogo敦化精品概念櫃 ','<img src=\"http://www.lart-asia.com/weneed/images/images/duanhua1.jpg\">\n<font color=\"cb418f\"><strong>\n\nSOGO敦化館4F \nWENÉE / WENEED BEAUTY　薇妮體雕　極緻精品內衣　精品概念櫃 \n台北市敦化南路一段246號4樓 , NEW OPEN~! \n電話(8862)2721-2331\n\n即日起至4/5止為慶祝新櫃開幕，特別推出獨家敦化館優惠活動\n\n從來沒有過的慶祝優惠只在敦化櫃獨家享有!在這裡好康報給大家:)\n還有為各位準備了來店小禮!難得的大好機會可別錯過了呢','en_US','2011-03-11 02:51:07',1,'NEW OPEN~! 好康特報'),(16,'溫柔優雅，薇妮新色','\n<pre>\n<strong><font color=\"999999\">為了讓薇妮客戶有更多樣的選擇，薇妮體雕精緻塑身系列，量身訂製款推出新色囉！</font>\n<strong><font color=\"d19ecc\" size=\"2.5\"> \n　　　　　　　　　　~ 冬日浪漫粉紫新色 ~\n\n　　　　　　　　　　　溫暖浪漫全新包覆\n　　　　　　　天使羽毛般地浪漫氛圍，溫暖包覆全身\n　　　　　　　　　我的冬天，暖和地無話可說‧‧‧\n　　浪漫的薇妮，與我一同打造屬於自己最溫暖舒適的新尺度冬日\n\n</pre>','en_US','2010-11-13 06:07:08',1,'◇LAVENDER ◇薰衣紫'),(12,'薇妮體雕，雕塑健康與美麗的專家','\n\n<font color=\"999999\">\n<FONT SIZE=4>因為專業，更懂得呵護女人</FONT>  \n<FONT SIZE=4>薇妮體雕，雕塑健康與美麗的專家</FONT>  \n\n採訪撰文╱張玉櫻\n諮詢╱晨陽實業有限公司經理  林美昭\n攝影╱顏甄儀\n\n<H4>專業技術的優勢，設計出極佳塑身衣</H4>\n    Weneed beauty！「薇妮體雕」的英文名稱，開門見山地點出了晨陽公司創立「薇妮體雕」的用意！晨陽實業有限公司林美昭經理表示，晨陽公司成立於民國85年，從專業用品起家，至今已經15年了！早期量身訂製多功能彈性衣，提供各專業機構使用，在醫界頗受好評！\n    後來因為有許多使用者發現晨陽的彈性衣塑身效果佳，既舒服又好穿，才會讓晨陽將新產品轉向美容、美體領域，專為愛美女性及產後媽咪快速恢復身材，因而設計了「薇妮體雕」系列！其優勢就是有晨陽的專業技術內涵，因此效果非常優良，深受女性朋友歡迎與喜愛，也掀起了「量身訂製」塑身衣的風潮！\n\n<H4>靠口碑、品質、平價行銷</H4>\n    晨陽一直秉持低調、少做廣告、不找名人代言的理念，寧可把資金投入產品研發與回饋消費者，讓消費者用最實在的價錢買到最需要的產品！然而「好東西不寂寞」，「薇妮體雕」完全靠消費者「口碑」相傳──有效調整身型、好穿、價格實惠、不花冤枉錢……，獲得消費者的一致好評！ \n\n<H4>什麼樣的塑身衣？能與美麗、健康、自信有關？</H4>\n    氣質極佳的林美昭經理，用她一貫平穩且堅定的口吻說，能夠讓顧客從「薇妮體雕」的系列產品中，找到美麗、健康與自信，是他們堅持繼續做最佳塑身衣的原動力！\n    因為是從專業彈性衣起家，所以薇妮體雕的產品都是嚴選專業等級的高彈性透氣布料，無論縱向、橫向拉力彈性都很均衡，讓肌膚觸感溫和，長時間穿著也能感到很舒適、穿得住，這對消費者來說是很重要的！否則花大錢訂做一件穿不住的塑身衣，可說是找罪受、白花錢了！此外，薇妮體雕的塑身衣效果佳，連不正的體態都能調整過來，這是有著多年製作專業級產品的晨陽公司，能在業界屹立不搖的緣故！經典的產品包括：\n<B>◎連身體雕褲</B>\n    這是薇妮體雕系列產品中最具特色的精品，根據每位媽咪及愛美女性不同身材的不同需求，由專業體雕師測量出全面資料，再單一打版、一體成型剪裁，完美曲線一氣呵成，製成每位顧客的專屬產品。\n    連身體雕褲的特殊設計，能完整包覆副乳、有效收攏後背肉、呈現胸部完美曲線，並強化緊縮腹部、大腿及拉提臀部。所以在穿著連身體雕褲一段時間後，自然能夠擁有迷人曲線！再加上緊縮上腹部時能使食量變小，更讓窈窕效果加乘！無論是產後媽咪或任何想要擁有健美身材的女性，都是一個很有效的輔助工具。\n    此外，薇妮體雕在下身採用開襠式設計，只要再套上自己的小褲褲，如廁就很方便了，真是貼心的設計！從此不必在廁所內被難解難穿的塑身衣搞得滿頭大汗了！而乳房部位鏤空的設計，可搭配哺乳內衣，就能讓媽咪輕鬆餵寶寶喝母乳囉！\n    林美昭經理建議，女性在25歲之後、不論有無生小孩都要開始穿連身體雕褲，產後媽咪更需要！把握產後4個月內黃金期，認真穿塑身衣，調整、修正、快速恢復身材的效果很好。即使是生完小孩多年的媽咪，現在才穿也還來得及喔！\n<B>◎產後塑身褲</B>\n    更有效地提腹、加壓、提臀，林美昭經理說，許多穿過的產後媽咪們都讚不絕口呢！同樣採用貼心的下身開襠式設計，如廁及更換產墊更方便。\n<B>◎神奇胸托</B>\n    採用自肩部到下胸圍無胸罩式「U型線」設計，有效收攏、側推，能將腋下及前胸的多餘肉肉，收攏到正確位置，美化胸型。無罩杯開放設計獲得國家專利，胸托兩側崁入八顆永久性磁石，可強化胸型。不只產後媽咪愛用，只要是胸部開始發育的女孩們，都很適合穿這款胸托。\n<B>◎因應不同需求的各式塑身產品</B>\n    其他產品還有九分褲、高腰褲、低腰褲、胸衣（神奇胸托延長版，連腹部都能緊實）、面罩（可緊實臉部）、塑臂（bye bye蝴蝶袖）、纖腿套（bye bye蘿蔔腿）……，量身產品從頭到腳都有，因應每個人想要美麗的部位不一樣，因此薇妮體雕不是只有簡單的塑身產品而已。\n\n<H4>成功的條件！</H4>\n    林美昭經理很自信地說，市面上塑身衣產品眾多，但是薇妮體雕產品的專業獨特性，以及貼心的售後服務是無可取代的！例如：\n◎導入專業壓力原理，堅持一人一版：為顧客量身打造獨一無二的塑身衣。\n◎專業級品質挑選：嚴選專業等級高彈性纖維布料，透氣、貼身、彈性回縮力佳且耐用、不易變形，長時間穿著一樣感到透氣舒爽無負擔。此外，嚴格的工藝標準，完美收、提、補，能塑造出玲瓏有美感的身材曲線。\n◎	溫和漸進，打造理想曲線：絕對不用壓迫的方式營造出胸部及腰部的曲線，因為這樣是非常不舒服、影響健康且無法長期穿著的。薇妮體雕同時重視美麗與健康，採用溫和漸進的方式，並提供必要的「免費」減吋調整服務，一吋一吋看到優美曲線逐漸雕塑出來！\n◎	貼心的售後服務：顧客直接來會館修改，只需約30分鐘就可修改完成！外縣市顧客可寄回產品及修改尺寸，4天內就可修改完成寄回顧客手上。減吋都不收費、不限次數，就是要鼓勵身型改進有成的顧客；也有國外顧客寄回修改尺寸呢！貼心、有效率的售後服務，就是全心為顧客著想的體現！\n\n<H4>堅持走對的路！</H4>\n    晨陽創立之初，曾有人說這樣的企業不會超過2年！林美昭經理帶著感恩的口吻說：「靠著主耶穌基督的帶領，我們已經走過15個年頭！」\n    林美昭經理也要感謝多年來所有支持晨陽的顧客，看到客戶滿意的神情是晨陽努力的動力。為顧客塑造健康、美麗與自信這條路，將會繼續走下去！\n','en_US','2010-11-01 07:30:45',1,'「嬰兒與母親」2010年10月份採訪內容 '),(30,'嬰兒與母親  NO.413','<img src=\"http://www.lart-asia.com/weneed/images/images/of_babemom.jpg\">\n<font color=\"cb418f\"><strong>\n薇妮嚴選歐美進口專業SPANDEX高彈性纖維絲面料，輕飄飄包覆全身，獨特裁版打造最優質的塑身衣!','en_US','2011-03-09 11:08:48',1,'獨特裁版打造最優質的塑身衣~'),(31,'ELLE NO.234','<img src=\"http://www.lart-asia.com/weneed/images/images/of_elle.jpg\">\n<font color=\"cb418f\"><strong>\n\n擁有醫師強力推薦的薇妮~讓您體驗微調曲線的張力!','en_US','2011-03-09 11:12:33',1,'~讓您體驗微調曲線的張力! '),(32,'薇妮邀請卡寄出囉~','<img src=\"http://www.lart-asia.com/weneed/images/images/of_invataion.jpg\">\n<font color=\"cb418f\"><strong>\n薇妮體雕 敦化概念櫃正式開幕囉~!在4樓唷!!大家收到邀請卡了嗎? 為了慶祝開幕，現在起只要消費或是憑邀請卡至專櫃，即可獲得精美小禮品一份!(送完為止) 好好把握機會 go go go~~!!! 如還沒收到邀請卡的朋友們，想洽詢相關資訊請撥打美麗諮詢專線 08090-34567 我們將會有專人為您服務~!!','en_US','2011-03-09 11:16:36',1,'薇妮體雕 敦化館4樓開幕活動開跑~!!'),(19,'facebook~薇妮美麗祕密~','<img src=\"http://www.lart-asia.com/weneed/images/20101205a.jpg\">\n<strong><font color=\"999999\">\n親愛的~薇妮facebook啟動了!裡面不但會提供給您薇妮最新活動、也會\n分享給每位時尚完美女性 保養/塑身/健康/流行/生活/美麗新鮮事\n\n與妳分享美麗秘密中的秘密...*\n\n讓我們跟著 *薇妮* 一起成為完美的 精 品 女 人\n\n         \n\n馬上去看看--> <a href=\"http://www.facebook.com/weneed\">facebook.薇妮美麗祕密WENEED BEAUTY</a>','en_US','2010-12-06 02:43:15',1,'薇妮在facebook展開囉!大家趕快加入讓自己成為窈窕的 精 品 女 人'),(21,'柯夢波丹1月份報導','<img src=\"http://www.lart-asia.com/weneed/images/20110106b.jpg\" width=\"500\"><font color=\"cb418f\"><strong>\n\n柯夢波丹1月份報導\n\n一月，驚艷開始','en_US','2011-01-06 10:16:03',1,'一月，驚艷開始'),(20,'12月柯夢報導','<img src=\"http://www.lart-asia.com/weneed/images/20101210a.jpg\">\n<font color=\"cb418f\"><strong>\n\n精雕細琢的薇妮體雕，遇上熱情的妳會擦出什麼樣的火花呢?薇妮提供給你幾\n個搭配的小訣竅，讓妳在派對中成為最亮眼的焦點!更多詳細報導請參閱12號\n柯夢波丹，讓妳的冬日驚艷不間斷!','deleted','2010-12-11 01:27:30',1,'12月柯夢波丹中，薇妮體雕教妳如何在華服底下呈現精緻身型!'),(22,'第九屆　婦幼菁品大賞','<img src=\"http://www.lart-asia.com/weneed/images/20110106a.jpg\" width=\"500\"><font color=\"cb418f\"><strong>\n\n第九屆　婦幼菁品大賞\n\n薇妮體雕　榮選為年度最佳人氣品牌','en_US','2011-01-06 10:42:28',1,'薇妮榮選為年度最佳人氣品牌'),(23,'薇妮體雕春節休假日期','<font color=\"cb418f\"><strong>\n親愛的顧客，薇妮體雕過年春節休假日為2/2(三)~2/7(一)\n2/8(二)開始正常上班喔！\n薇妮體雕各位新春佳節愉快，美麗一整年！\n','en_US','2011-02-01 05:16:49',1,'2/2(三)~2/7(一)薇妮體雕休假喔!'),(24,'薇妮體雕 新據點','<img src=\"http://www.lart-asia.com/weneed/images/20110225a.jpg\">\n<font color=\"cb418f\"><strong>自三月六號開始，薇妮體雕進駐敦南SOGO囉!\n歡迎您臨櫃享受我們的服務!','en_US','2011-02-25 05:31:15',1,'薇妮體雕即將在敦化SOGO與您見面囉!'),(25,'COSMOPOLITAN NO 237','<img src=\"http://www.lart-asia.com/weneed/images/images/cos237.jpg\">\n','en_US','2011-03-04 01:21:28',1,'柯夢波丹 NO 237 '),(26,'嬰兒與母親 NO. 412','<img src=\"http://www.lart-asia.com/weneed/images/images/nepf412.jpg\">\n','en_US','2011-03-04 01:35:33',1,'嬰兒與母親 NO. 412'),(27,'媽媽寶寶 NO.287','<img src=\"http://www.lart-asia.com/weneed/images/images/mb.jpg\">\n','en_US','2011-03-04 01:39:11',1,'媽媽寶寶 NO.287'),(28,'COSMOPOLITAN NO.241','<img src=\"http://www.lart-asia.com/weneed/images/images/cos241.jpg\">\n<font color=\"cb418f\"><strong> 柯夢波丹 NO.241 教你如何搭配薇妮體雕神奇胸托，並用集中托高的微調，輕鬆誘惑他的心!','en_US','2011-03-04 01:41:38',1,'柯夢波丹 NO.241教你如何搭配薇妮體雕神奇胸托'),(29,'媽媽寶寶 NO.289','<img src=\"http://www.lart-asia.com/weneed/images/images/mb289.jpg\">\n<font color=\"cb418f\"><strong>\n\n～３月　ＭＡＲＣＨ～活動開跑\n\n極纖超選---名媛爭先指定\n\n人氣經典---熱賣新機S曲線\n \n','en_US','2011-03-04 01:57:50',1,'3月活動開跑囉~'),(56,'ANN 張心妍 分享產後塑身祕密','<font color=\"cb418f\"><strong>\n心妍登上YAHOO奇摩首頁了呢! 大家都說她瘦了，才短短的產後3個月! 大家都\n在詢問她的產後塑身秘密..... 其實就是 塑身衣權威品牌 - 薇妮體雕 - \n\n<img src=\"http://www.lart-asia.com/weneed/images/images/ann_2011yahoo.jpg\"> \n\n\n\" 很多人都很好奇，我現在到底是啥模樣，最近我終於拾起一點信心啦​，塑身衣\n也穿了兩個多月了，是該出來讓大家看看成效了！\n現在雖然還沒完全恢復到懷孕前的狀態，但只差幾公斤了，我現在似​乎也愛上穿\n塑身衣的感覺了，“薇妮體雕”的塑身衣穿起來真的很舒​服。我知道他們家還有\n個路線是做燒燙傷病患的修復衣，所以根本不​用怕皮膚過敏，這透氣度真的很好\n，我大熱天穿著它出門也部會不舒​服。塑身衣最重要的就是透氣度了，要不然穿\n不住一樣沒效用！每天​穿著它，也讓我的皮膚不會鬆垮垮的，感覺更緊實！出不\n了門去運動，我也會在家利用baby睡覺時做仰臥起坐、抬腿​.....等小運動，\n上星期參加朋友的婚禮，大家都說我瘦了，我​聽了...真的很開心，加油，我身\n體的四公斤肥肉，你們快走吧！\"\n\n\n<img src=\"http://www.lart-asia.com/weneed/images/images/ann_julyblog.jpg\">  \n  <a href=\"http://tw.myblog.yahoo.com/ann041899/article?mid=10002&prev=\">http://tw.myblog.yahoo.com/ann041899/article?mid=10002&prev=</a>  -1&next=9832\n\n\n* 精品塑身衣權威品牌 ---- 薇妮體雕 *\n\n服務專線08090-34567\n\n\n\n\n','en_US','2011-07-15 02:03:45',1,'薇妮體雕 榮登YAHOO TAIWAN首頁'),(34,'BODY NO.147','<img src=\"http://www.lart-asia.com/weneed/images/images/body_ofw.jpg\">\n<font color=\"cb418f\"><strong>\nBODY 在本期＂調整型內衣品牌大揭密＂單元中特訪薇妮體雕\n','en_US','2011-03-19 02:40:16',1,'調整型內衣品牌大揭密~特訪薇妮體雕'),(35,'全省專人到府量身','<font color=\"cb418f\"><strong>\n親愛的各位~在此要特別告知大家\n別忘了\n薇妮體雕　量身訂做款塑身衣　提供全省專人到府量身服務呢！\n只要撥電話到　美麗諮詢專線　08090-34567 就可得知相關訊息\n無論您住在哪，薇妮體雕會盡最大力量為您服務唷：）','en_US','2011-03-22 03:45:58',1,'為了您的便利，薇妮提供全省專人到府量身'),(36,'SOGO NEWS ~','<img src=\"http://www.lart-asia.com/weneed/images/images/356.jpg\">\n<font color=\"cb418f\"><strong>\n現在起只要在SOGO敦化館精品概念櫃刷卡滿3000、5000、6000\n即可分別享有SOGO特定3,5,6期零利率的優待呢~(至12/31止)讓\n您理財更便利!','en_US','2011-03-30 06:42:19',1,'3,5,6期零利率、消費理財更便利'),(37,'薇妮體雕贊助經典劇作SWANLAKE','<img src=\"http://www.lart-asia.com/weneed/images/images/swanlake.jpg\">\n<font color=\"cb418f\"><strong>\n\n前一陣子由納塔莉波曼主演的火紅電影\"BLACK SWAN\"＜黑天鵝＞，勾\n起了從小充滿憧憬天鵝湖的美麗經典故事\n\"shanghai 薇妮體雕 \"於去年贊助俄羅斯莫斯科國立芭蕾舞團著名劇\n作＜天鵝湖＞演出，俄羅斯莫斯科芭蕾舞團為著名國際舞團其一、時常\n於世界各地優雅演出著名舞劇，不知道大家有沒有看過呢 ? 希望親愛\n的大家也能如天鵝湖這齣經典名作翩翩起舞、找出美麗的經典永恆。','en_US','2011-03-30 09:33:45',1,'俄羅斯莫斯科國立芭蕾舞團著名劇作＜天鵝湖＞'),(38,'COSMOPOLITAN　NO.243','<img src=\"http://www.lart-asia.com/weneed/images/images/cos243.jpg\">\n<font color=\"cb418f\"><strong>\n\nWEEKEND WHAT TO DO\n\n｛女人的約會法寶｝\n\n出門跟他約會，害怕身體的小圈點露餡？穿上塑身衣就不用擔心穿幫，\n讓它來雕塑好身材。這件連身款體雕長褲有秘密法寶，高彈性透氣纖\n維布料、貼身不緊繃完美包覆，即使穿上一天也不怕。安心地讓男友\n欣賞你的外在美，其他的就交給它吧','en_US','2011-04-07 05:11:33',1,'WEEKEND WHAT TO DO '),(39,'VOGUE　NO. 175','<img src=\"http://www.lart-asia.com/weneed/images/images/VOGUE%20NO175.jpg\">\n<font color=\"cb418f\"><strong>\n\n薇妮晶粹極塑體雕衣\n\n以進口560丹高彈性透氣纖維，經由專業壓力精密計算\n設計，一人一版良身製作，貼身不緊繃並能隨生活律動\n調整身體曲線。腋下、胸側、腹部及腿部做多片式強化\n效果，並應用３Ｄ剪裁增強力學引導調整臀部，完美曲\n線一氣呵成，美麗不設限。','en_US','2011-04-07 08:23:44',1,'瘦身最佳選擇~晶粹極塑體雕衣'),(40,'VOGUE　ANGELS　S/S ISSUE APR.','<img src=\"http://www.lart-asia.com/weneed/images/images/VOGUEANGEL_2.jpg\">\n<font color=\"cb418f\"><strong>','en_US','2011-04-07 08:48:33',1,'好萊塢夢，醫美級專業微調身型'),(43,'嬰兒與母親　　　　　　　NO.415','<img src=\"http://www.lart-asia.com/weneed/images/images/bm5.jpg\">\n<font color=\"cb418f\"><strong>','en_US','2011-05-11 06:47:46',1,'[母節  薇獻禮] 送媽咪的最好禮物'),(44,'媽媽寶寶　　　　　　　　NO.291','<img src=\"http://www.lart-asia.com/weneed/images/images/mb5.jpg\">\n<font color=\"cb418f\"><strong>','en_US','2011-05-11 06:49:26',1,'以最溫柔的觸感體貼包覆'),(45,'ELLE   　　NO.236','<img src=\"http://www.lart-asia.com/weneed/images/images/ELLE5.jpg\">\n<font color=\"cb418f\"><strong>\n\n薇妮體雕於ELLE 2011 五月月號 雙頁報導\n\nWENEE -------- HAUTE COUTURE\n\n憑藉著一股對美的熱情，薇妮體雕已邁入20年的專業訂製經驗，提\n供量身訂製機能型的體雕衣，不斷為女性重新創造優雅典範。從精\n挑細選頂級面料、準確嚴密的尺寸測量，到一人一版的純手工製作\n，薇妮體雕堅持提供最優質的產品，不但受到許多醫師推薦，在政\n商名流間也得到優質口碑。\n\n不為潮流 永遠經典\n\n時尚潮流順其萬變，但優美的體代才是真正的百搭重點。薇妮體雕\n多年來致力於高機能型體雕衣製作，經過不斷改良設計出最適合華\n人身材的產品。產品由測量到製作完成所有細節絕不輕忽，準確精\n算每分每吋數字，以恰到好處不影響健康的彈性壓力讓您展現穠纖\n合度的曲線。導入專業技術開發的3D立體裁版將體雕衣效能更極緻\n化，緊實、拉提效果加倍明顯，曲線自然凹凸有致出眾魅力使您成\n為人群中的焦點。\n\n','en_US','2011-05-25 03:52:26',1,'-- 曲線  重新定義 --'),(51,'COSMOPOLITAN  　NO.245','<img src=\"http://www.lart-asia.com/weneed/images/images/cosjune2011officialwebsite.jpg\">\n<font color=\"cb418f\"><strong>\n\n這次在柯夢波丹不但有\"WENEE 薇妮體雕 \" 的詳盡介紹，在繽紛家居\nSPLENDID LIVING 單元裡也可看到 晶粹極塑薰衣紫色連身短褲 的\nNEWS　新知 !','en_US','2011-06-24 06:44:13',1,'柯夢波丹 2011六月號'),(54,'ELLE   NEWS ','<img src=\"http://www.lart-asia.com/weneed/images/images/elle_april_internetofficial website_1.jpg\">\n\n<img src=\"http://www.lart-asia.com/weneed/images/images/elle_april_internetofficial website.jpg\">\n<font color=\"cb418f\">\n\n薇妮體雕精品概念櫃尊貴禮遇\n\n為了給您更舒適方便的服務，薇妮體雕於敦化SOGO新開幕的精品概念櫃\n，提供舒適隱密的獨立專屬更衣間及諮詢區，帶給您的不只是傳統對塑\n身衣的看法，更進一步為您提供專業的諮詢及量身服務，如好萊塢明星\n般的尊寵禮遇即刻擁有。 免費諮詢專線 08090-34567 \n\n\n\n\n','en_US','2011-06-25 06:08:12',1,'ELLE 網路新訊 --- 薇妮體雕精品概念櫃尊貴禮遇'),(52,'張心妍  保養身材第一選擇  薇妮體雕','<img src=\"http://www.lart-asia.com/weneed/images/images/ann_blogmay_offcialwebsite.jpg\">\n<font color=\"cb418f\">\n\n還記得海角七號中飾演櫃檯妹的張心妍嗎? 嫁給蔣友柏表哥的她於去年\n年底多了一個新身分、升格為媽咪囉~!\n\n健康甜美外型的她也選擇了 塑身衣權威品牌---- 薇妮體雕 做為產後\n瘦身回復身才的最佳武器唷~ 心妍 選擇的是薇妮體雕 晶粹極塑款 連\n身塑身衣做為日常保養最佳單品!\n\n\n...\"\"剛生完的沒幾天，自已以為身材消的很快，但是我才發現，骨盆都被撐寬了，我自己帶的睡褲居然穿不上去，肚皮也鬆垮了不少，就好像豆腐似的！！ 很多朋友很早就提醒我，產後要穿塑身衣，我這次選擇的是“薇妮體雕”（www.WNBEAUTY.com.tw)，布料選擇的是她們 的“晶粹極塑”系列。畢竟要穿很久，而且夏天這樣熱，布料的透氣度是很重要的！ 產後的一個多星期後，有專人來到月子中心幫我量身，再過一個星期，我的塑身衣就送到啦！我第一次穿的時候，覺得這是一個人辦不到的事，我又不敢很大力拉，結果品牌小姐跟我說，不用擔心，這不可能會破的，是高彈性布料！ 穿好塑身衣，我的心得就是，很多部位都回到他自己屬於的地方啦，尤其是上圍的部份，自己看得很滿意阿！哈哈！現在穿了一個星期，我反而不穿會有點不習慣。其實穿了塑身衣，不只雕塑線條，我覺得我腰部也比較有支撐，不會這麼痠，我也發現我的肚皮慢慢再恢復緊實了！！ 畢竟做月子期間也不能想減肥，還是要把自己身體捕好，這時就夠塑身衣來維持線條！過一陣子我再跟大家分享，我穿塑身衣的效果如何囉！！ \"\"\n\n\n  <a href=\"http://tw.myblog.yahoo.com/ann041899/article?mid=9298&prev=9626&next=9127See\">http://tw.myblog.yahoo.com/ann041899/article?mid=9298&prev=9626&next=9127See</a>   More\n','en_US','2011-06-25 01:29:38',1,'心妍在blog裡分享產後塑身力器'),(49,'BODY  2011年5月 金纖大賞','<img src=\"http://www.lart-asia.com/weneed/images/images/body_may_officialwebsite.jpg\">\n<font color=\"cb418f\"><strong>\n\n薇妮體雕 晶粹極塑 連身褲款塑身衣 --- 入圍Body雜誌2011年5月份\n金纖大賞 * 纖提賞\n','en_US','2011-06-21 08:30:26',1,'WENEE 薇妮體雕　榮選金纖大賞'),(50,'漂亮甜心 ---- 張心妍 　產後下一步','<font color=\"cb418f\"><strong>\n還記得海角七號中的一砲而紅的亮眼櫃檯妹-張心妍嗎?嫁給蔣友柏表\n哥的心妍於去年開始多了一個新身分，升格為媽咪囉~!一起來分享她\n的喜悅吧\n<iframe width=\"425\" height=\"349\" src=\"http://www.youtube.com/embed/NvJD2mZTcXI\" frameborder=\"0\" allowfullscreen></iframe>\n\n偷偷告訴大家~為了產後能更快速的恢復身材，聰明的心妍早在懷孕時\n就選擇了薇妮體雕 \"晶粹極塑塑身衣\" 來擔任產後恢復身材的秘密武\n器! 產後的黃金瘦身期就跟著薇妮體雕一起為曲線把關!\n\nＡＮＮ　張心妍的部落格分享 check*－－＞ <a href=\"http://tw.myblog.yahoo.com/ann041899/article?mid=9298&prev=-2&next=9127&page=1&sc=1#yartcmt\">下一步---塑身衣</a>','en_US','2011-06-21 08:32:16',1,'心妍保養身型的第一選擇'),(53,'BEAUTY　大美人','<img src=\"http://www.lart-asia.com/weneed/images/images/beauty_6officialwebsite.jpg\">\n<font color=\"cb418f\">\n\n以名媛雜誌著稱的 BEAUTY大美人 雜誌本月份針對薇妮體雕報導最新\n相關資訊~ 讓讀者更進一步認識塑身衣權威品牌薇妮體雕~!','en_US','2011-06-25 01:32:37',1,'大美人 2011 6 月號 　名媛雜誌'),(55,'薇妮體雕　消費者評價第一名','薇妮榮獲100年消費者評價第一名殊榮\n- 獎狀 -\n<img src=\"http://www.lart-asia.com/weneed/images/images/reward_p.jpg\"> \n\n- 獎杯 -\n<img src=\"http://www.lart-asia.com/weneed/images/images/reward_s.jpg\"> \n','en_US','2011-07-07 01:40:41',1,'薇妮榮獲100年消費者評價第一名殊榮'),(57,'COSMOPOLITAN  　NO.247','<font color=\"cb418f\"><strong>\n\nCosmopolitan 2011 SEPT 9月份的柯夢波丹裡 薇妮體雕 的品牌主視覺畫面是帶有神祕感的黑色​色調呈現,加上詳盡的品牌與相關產品解說呢! 並且也有活力演員 張心妍 的真心推薦\n\n<img src=\"http://www.lart-asia.com/weneed/images/images/cos_2011aug.jpg\"> \n\n在本期P.181 也可看到 薇妮體雕 薰衣紫體雕衣\n\n<img src=\"http://www.lart-asia.com/weneed/images/images/cos_2011aug_2.jpg\"> \n\n\n','en_US','2011-08-05 08:50:27',1,'柯夢波丹  2011 9月號');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news_category`
--

DROP TABLE IF EXISTS `news_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `news_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) DEFAULT NULL,
  `description` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news_category`
--

LOCK TABLES `news_category` WRITE;
/*!40000 ALTER TABLE `news_category` DISABLE KEYS */;
/*!40000 ALTER TABLE `news_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_tags`
--

DROP TABLE IF EXISTS `post_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_tags` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postid` int(11) NOT NULL,
  `tagid` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_tags`
--

LOCK TABLES `post_tags` WRITE;
/*!40000 ALTER TABLE `post_tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `post_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product`
--

DROP TABLE IF EXISTS `product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `category_id` int(11) NOT NULL,
  `sn` varchar(512) COLLATE utf8_unicode_ci DEFAULT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `spec` text COLLATE utf8_unicode_ci,
  `lang` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `created_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int(11) NOT NULL,
  `image` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `thumb` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=43 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product`
--

LOCK TABLES `product` WRITE;
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
INSERT INTO `product` VALUES (21,'<strong>連身款體雕長褲　雪亮白</strong>',1,'A01W','<font color=\"#999999\">　<font face=\"Arial\" color=\"#999999\">560den高彈性透氣纖維、量身訂作製成，經由專業壓力精密計算設計，加強四大部位，一體成形，完美修整全身\n\n胸　完整包覆腋下副乳，並集中、托高胸型\n腹　由３層布片施加適度壓力、使小腹平坦\n腿　對於女性最容易囤積的大腿外側特別加\n　　強，強化效果\n臀　力學引導推脂讓臀部托高在該有部位，\n　　輕而易舉擁有翹臀\n\n　*可額外加強背部壓力、與虎背熊腰說拜拜*\n　　　　　　*可選擇訂製褲長*\n\n</font>',NULL,NULL,'2010-11-02 01:30:31',0,'images/upload/353c5d59cc1a6a9247fe2a83dcadf7c1.jpg','images/upload/thumbs/167d834abe87aa5a986d18a52982cfe9.jpg'),(22,'<strong>連身款體雕短褲　性感紅</strong>',1,'A01R','<font face=\"Arial\" color=\"#999999\">560den高彈性透氣纖維、量身訂作製成，經由專業壓力精密計算設計，加強四大部位，一體成形，完美修整全身\n\n胸　完整包覆腋下副乳，並集中、托高胸型\n腹　由３層布片施加適度壓力、使小腹平坦\n腿　對於女性最容易囤積的大腿外側特別加\n　　強，強化效果\n臀　力學引導推脂讓臀部托高在該有部位，\n　　輕而易舉擁有翹臀\n\n　*可額外加強背部壓力、與虎背熊腰說拜拜*\n　　　　　　*可選擇訂製褲長*\n\n</font>',NULL,NULL,'2010-11-02 01:44:08',0,'images/upload/4892884f15d26ad434e63b0d4ee89677.jpg','images/upload/thumbs/052c450e0d90ed1222966a6224375a6b.jpg'),(23,'<strong>連身款體雕短褲　薰衣紫</strong>',1,'A01P','<font face=\"Arial\" color=\"#999999\">560den高彈性透氣纖維、量身訂作製成，經由專業壓力精密計算設計，加強四大部位，一體成形，完美修整全身\n\n胸　完整包覆腋下副乳，並集中、托高胸型\n腹　由３層布片施加適度壓力、使小腹平坦\n腿　對於女性最容易囤積的大腿外側特別加\n　　強，強化效果\n臀　力學引導推脂讓臀部托高在該有部位，\n　　輕而易舉擁有翹臀\n\n　*可額外加強背部壓力、與虎背熊腰說拜拜*\n　　　　　　*可選擇訂製褲長*\n\n</font>',NULL,NULL,'2010-11-02 01:44:51',0,'images/upload/100087c5d99ad54d0c420778acdbac00.jpg','images/upload/thumbs/3b6ebefd0f234094ead262d6145b3c74.jpg'),(25,'<strong>連身款體雕短褲　優雅膚</strong>',1,'A01Y','<font face=\"Arial\" color=\"#999999\">560den高彈性透氣纖維、量身訂作製成，經由專業壓力精密計算設計，加強四大部位，一體成形，完美修整全身\n\n胸　完整包覆腋下副乳，並集中、托高胸型\n腹　由３層布片施加適度壓力、使小腹平坦\n腿　對於女性最容易囤積的大腿外側特別加\n　　強，強化效果\n臀　力學引導推脂讓臀部托高在該有部位，\n　　輕而易舉擁有翹臀\n\n　*可額外加強背部壓力、與虎背熊腰說拜拜*\n　　　　　　*可選擇訂製褲長*\n\n</font>',NULL,NULL,'2010-11-02 01:46:14',0,'images/upload/6831ea8aa803fdc98c2cbfc76c0ff8d6.jpg','images/upload/thumbs/d723a16fa34a009b301b68c4426d6ef5.jpg'),(26,'<strong>低腰褲</strong>',2,'B01L','<font color=\"#999999\">\n*黑、膚色可做選擇*\n專業４２０丹高彈性透氣纖維製成、透氣排汗\n\n腹部採多片式加壓彈力網層設計、精密計算壓力係數專業製成，有特殊需求的您可經由專業人士推薦使用\n加強部位\n\n腹　下腹部多片式彈性加壓臀部網層設計\n臀　力學引導臀托設計，將拖高渾圓俏麗\n腿　420den溫和彈性壓力，緊束調整腿型\n\n',NULL,NULL,'2010-11-02 01:47:55',0,'images/upload/ea5a1a6f067b1e7a324f232d0f107de6.jpg','images/upload/thumbs/ed6231353e85c184f3abf1c48ea94811.jpg'),(27,'<strong>高腰短褲　星夜黑</strong>',2,'B01S','<font color=\"#999999\">\n*黑、膚色可做選擇*\n專業４２０丹高彈性透氣纖維製成、透氣排汗\n\n腹部採多片式加壓彈力網層設計、精密計算壓力係數專業製成，有特殊需求的您可經由專業人士推薦使用\n加強部位\n\n腹　下腹部多片式彈性加壓臀部網層設計\n臀　力學引導臀托設計，將拖高渾圓俏麗\n腿　420den溫和彈性壓力，緊束調整腿型\n',NULL,NULL,'2010-11-02 01:50:26',0,'images/upload/1eab05f54354ff16f36e74e4204764dc.jpg','images/upload/thumbs/0134724137303535fcdfb78bcf3299cc.jpg'),(28,'<strong>雕纖束衣  /  纖腿套</strong>',3,'C01','<font face=\"Arial\" color=\"#999999\"><strong>雕纖束衣</strong>\n獨家專業壓力技術設計，可依需求選擇長、短袖，貼身緊緻，緊實後背、腋下及手臂多餘脂肪，美化後背曲線與手臂線條</br>\n<strong>纖腿套</strong>\n精算專業壓力數據製作，溫和舒適，讓常需使力的小腿得以藉由適度壓力輔助獲得輕鬆，與惱人的蘿蔔腿説bye bye~\n</font>\n',NULL,NULL,'2010-11-02 01:53:03',0,'images/upload/95d577f556d902f9e408bdb44420dc8e.jpg','images/upload/thumbs/8e55f63c9df6ab31ff2bdf7ef358e624.jpg'),(29,'<strong>雕纖面罩</strong>',3,'C02','<img src=\"http://www.lart-asia.com/weneed/admin/images/upload/thumbs/7c8428f227c8e7fb70cc37ac07310cf3.jpg\"><font color=\"#999999\">分為全開放及半開放兩種\n臉部及後腦採開放式設計，可緊實臉部及頸部線條，修飾臉部曲線，平整雙下巴，適用於夢寐小巧臉蛋的您</br>\n有特殊需求者，由專業人士建議使用',NULL,NULL,'2010-11-02 01:54:42',0,'images/upload/6ee6d1940074ac06e5cbb1474a0a1f1a.jpg','images/upload/thumbs/5de2832d287a416fae6740aecda66084.jpg'),(31,'<strong>神奇胸托</strong>',3,'C04','<font color=\"#999999\">無罩杯式力學 U 型線條設計，將胸部集中、托高，薇妮並榮獲國家專利，是擔心胸型下垂、外擴與有副乳的您的特力救星。（坊間充斥許多仿冒商品，真品才有最好效果，請消費者謹慎選擇）\n國家專利產品：新式樣第081207號\n\n',NULL,NULL,'2010-11-02 01:56:03',0,'images/upload/66f1d00950bdb50b93305f1b06aa5083.jpg','images/upload/thumbs/83a6d10dd157fdc6b04f310c9bc6f2e1.jpg'),(32,'<strong>雕纖束臂 / 固定胸罩</strong> ',3,'C05','<font color=\"#999999\"><strong>雕纖束臂</strong>\n分別有長、短袖款式可做選擇，可緊實手臂調整曲線、讓手臂線條更優美\n \n<strong>固定胸罩</strong>\n前胸設計可調式固定帶，使胸型定位在該有位置、完整包覆乳房 ，可滿足您不同的調整需求\n\n(有特殊需求者，由專業人士建議使用)\n\n\n',NULL,NULL,'2010-11-02 01:57:43',0,'images/upload/de1a7fd5facf9909e52356d2218b5395.jpg','images/upload/thumbs/666dcdf1e619e3d0041a443519e216ea.jpg'),(33,'<strong>磁能胸托</strong>',4,'D01','<font color=\"#999999\">無罩杯式力學 U 型線條設計，將胸部集中、托高，薇妮並榮獲國家專利，是擔心胸型下垂、外擴與有副乳您的救星</br>\n永久磁能鑲入胸部兩側共六顆，左右胸下各一顆、讓磁場能量作用於胸部對胸部線條嚴加要求的愛美女性有了頂級的胸部調理師\n\n\n\n',NULL,NULL,'2010-11-02 01:58:36',0,'images/upload/2ae174e043957247d42557642974e80b.jpg','images/upload/thumbs/e78cbf3abfc936f9a670ddd87dcf9814.jpg'),(34,'<strong>鍺晶胸托</strong>',4,'D02','<font color=\"#999999\">無罩杯式力學 U 型線條設計，將胸部集中、托高，薇妮並獨榮國家專利，是擔心胸型下垂、外擴與有副乳您的救星</br>\n鍺能晶片鑲入左、右兩側胸部，讓對胸部線條嚴加要求的愛美女性有了頂級的胸部調理師\n\n',NULL,NULL,'2010-11-02 01:59:15',0,'images/upload/61061dd5929c6d87f56daf26b7140ca7.jpg','images/upload/thumbs/8bfe269fa322181723412d785b0bed93.jpg'),(35,'<strong>產後塑身褲</strong>',5,'E01','<font color=\"#999999\">嚴選420den專業彈性纖維材質製成，專為媽媽快速恢復身材而設計讓媽咪們輕鬆為寶寶哺乳\n\n腹　除腹部多片式強化設計外，另以提腹帶\n　　原理設計，滿足產後腹部快速恢復需求\n\n腰　立體裁版特有加強腰部功能性\n\n臀　提臀帶雕塑臀型、定位恢復美麗俏臀\n\n\n開襠式貼心設計，貼身衣物可直接外穿，避\n免悶濕，方便如廁及更換產墊\n\n<a href=\"http://www.wretch.cc/blog/weneedbeauty\">看更多媽咪推薦心得</a>',NULL,NULL,'2010-11-02 01:59:58',0,'images/upload/7af5a35e6b3b12484874cd8a8d54bffc.jpg','images/upload/thumbs/bc2aca4ccecaa1a9ffd60f3fd2b47280.jpg'),(38,'<strong>連身款體雕長褲　星夜黑</strong>',1,'a01vb','<font face=\"Arial\" color=\"#999999\">560den高彈性透氣纖維、量身訂作製成，經由專業壓力精密計算設計，加強四大部位，一體成形，完美修整全身\n\n胸　完整包覆腋下副乳，並集中、托高胸型\n腹　由３層布片施加適度壓力、使小腹平坦\n腿　對於女性最容易囤積的大腿外側特別加\n　　強，強化效果\n臀　力學引導推脂讓臀部托高在該有部位，\n　　輕而易舉擁有翹臀\n\n　*可額外加強背部壓力、與虎背熊腰說拜拜*\n　　　　　　*可選擇訂製褲長*\n\n</font>',NULL,NULL,'2010-11-11 09:43:21',0,'images/upload/188d0d81f379de88dc91867d0cf68338.jpg','images/upload/thumbs/bd4f244e5e2e8369b123d9a336fbf89e.jpg'),(40,'<strong>連身款體雕長褲</strong>',5,'e02','\n<font face=\"Arial\" color=\"#999999\">嚴選560den高彈性透氣纖維、量身訂作製成，經由專業壓力精密計算設計，加強四大部位\n　\n胸　完整包覆腋下副乳，並集中、托高胸型\n　　，讓產後須特別注意胸部變形的媽咪們\n　　得以調整胸型\n腹　由３層布片施加適度壓力、使生產過後\n　　易鬆弛的小腹更緊實平坦\n腿　對於女性最容易囤積的大腿外側特\n　　別雙層加強，強化其功能\n臀　力學引導讓臀部托高在該有部位，擁有\n     翹臀輕而易舉\n　*可額外加強背部壓力、讓生產過的後腰、\n　　背部較脆弱的媽咪們有了更好的強化夥伴\n　　防護*\n　*開襠式貼心設計，貼身衣物可直接外穿，\n　　避免悶濕，方便如廁及更換產墊\n\n</font>',NULL,NULL,'2010-11-19 07:33:08',0,'images/upload/29a0004b6590f9ca0d3a743cab1d63c7.jpg','images/upload/thumbs/0e3ed62a255d54b6051be5d622d8a97b.jpg'),(42,'<strong>鍺晶體雕衣</strong>',4,'f01b',' <font face=\"Arial\" color=\"#999999\">560den高彈性透氣纖維、量身訂作製成，經由專業壓力精密計算設計，加強四大部位，一體成形，完美修整全身\n腹部及背部鑲入高科技奈米鍺能量晶片6片，前腹2片、腰背4片，透過專業設計將鍺晶置於人體相關經脈穴位，讓美麗與生活品質同時升級。\n\n　胸　完整包覆腋下副乳，並集中、托高胸型\n　腹　由３層布片施加適度壓力、使小腹平坦\n　腿　對於女性最容易囤積的大腿外側特別加\n     強，強化效果\n　臀　力學引導推脂讓臀部托高在該有部位，\n     輕而易舉擁有翹臀\n\n　*可額外加強背部壓力、與虎背熊腰說拜拜*\n　　　　　　*可選擇訂製褲長*\n\n</font>',NULL,NULL,'2011-02-01 07:40:02',0,'images/upload/316517673b38bdef91c4b0a12551da57.jpg','images/upload/thumbs/abc0efd1dd523c8140a60f0fa74f71a4.jpg');
/*!40000 ALTER TABLE `product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_category`
--

DROP TABLE IF EXISTS `product_category`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lang` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_category`
--

LOCK TABLES `product_category` WRITE;
/*!40000 ALTER TABLE `product_category` DISABLE KEYS */;
INSERT INTO `product_category` VALUES (1,'<strong>精緻塑身系列 - 連身塑身衣</strong>','zh_TW','</br>\n</br>\n<strong>薇妮體雕暢銷　NO1　經典精品</strong>\n</br>\n</br>\n嚴選歐美進口560丹專業高彈性透氣纖維</br>\n布料、量身訂作製成溫柔呵護肌膚，貼身</br>\n不緊繃完美包覆，能隨生活律動，猶如推</br>\n脂按摩一般呵護，讓您長時間穿著也能舒</br>\n適無負擔</br>\n'),(2,'<strong>夢幻雕塑系列 - 簡易量身款體雕褲</strong>','zh_TW','</br>\n</br>\n選用420ｄｅｎ高彈性纖維布料，透氣排</br>\n汗，共有短褲、六分褲與長褲可做選擇'),(3,'<strong>局部精雕系列</strong>','zh_TW','</br>\n</br>\n藉由薇妮體雕各項局部雕塑產品，讓對局部</br>\n身型不滿的您有了最貼心的看顧\n\n'),(4,'<strong>獨家系列 - 鍺晶磁石</strong>','zh_TW','</br>\n</br>\n<strong>獨家研發奈米鍺晶能量．高效能永久磁石系列</strong></br>\n</br>\n經研究團隊研發設計、嚴選優良鍺晶、磁石</br>\n能量鑲入產品內，對準人體穴位，讓您不但</br>\n把持美麗並提升生活品質喔!\n'),(5,'<strong>寶貝媽咪系列</strong>','zh_TW','</br>為讓辛苦的媽咪能擁有產後最佳恢復體態的</br>\n     黃金時間，薇妮體雕研發設計團隊透過深厚</br>\n     經驗值，為媽咪用心研發出各項設計\n');
/*!40000 ALTER TABLE `product_category` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_image`
--

DROP TABLE IF EXISTS `product_image`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_image` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `thumb` varchar(130) DEFAULT NULL,
  `image` varchar(130) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_image`
--

LOCK TABLES `product_image` WRITE;
/*!40000 ALTER TABLE `product_image` DISABLE KEYS */;
INSERT INTO `product_image` VALUES (1,2,'static/upload/tumblr_lmu3gt3bZv1qzhvpto1_400_thumb.jpg','static/upload/tumblr_lmu3gt3bZv1qzhvpto1_400.jpg'),(2,2,'static/upload/tumblr_lmu3gt3bZv1qzhvpto1_400_thumb_1.jpg','static/upload/tumblr_lmu3gt3bZv1qzhvpto1_400_1.jpg'),(3,2,'static/upload/cat_cute3_thumb.jpg','static/upload/cat_cute3.jpg');
/*!40000 ALTER TABLE `product_image` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `product_type`
--

DROP TABLE IF EXISTS `product_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `product_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(120) NOT NULL,
  `description` varchar(256) DEFAULT NULL,
  `spec` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `product_type`
--

LOCK TABLES `product_type` WRITE;
/*!40000 ALTER TABLE `product_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `product_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `auth_token` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  `role` varchar(12) COLLATE utf8_unicode_ci DEFAULT 'user',
  `email` varchar(120) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ident` (`account`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'admin','7110eda4d09e062aa5e4a390b0a572ac0d2c0220','admin','admin@admin.com');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2011-08-19 11:33:30
