<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


$route['default_controller'] = "welcome/index2";
$route['index2'] = "welcome"; // rota para acessar a página na versão antiga.
$route['404_override'] = '';

$route['install'] = "installation/installView";
$route['install/testmysql'] = "installation/testMysql";
$route['install/modaltemplate'] = "installation/modalTemplate";
$route['install/testcreatedb'] = "installation/testCreateDb";
$route['install/testdatehour'] = "installation/testDateHour";
$route['install/sendemailtest'] = "installation/sendEmailTest";
$route['install/doinstall'] = "installation/doInstall";

$route['dashboard/script/installconfigs'] ='script/installConfigs';
$route['dashboard/script/createAdminUser'] ='script/createAdminUser';
$route['dashboard/script/coordinatorslist'] ='script/coordinatorsList';
$route['dashboard/script/reporttgs'] ='script/reportGts';
$route['dashboard/script/reportpapers'] ='script/reportPapers';
$route['dashboard/script/reportposters'] ='script/reportPosters';
$route['dashboard/script/reportallpapers'] ='script/reportAllPapers';
$route['dashboard/script/reportallposters'] ='script/reportAllPosters';
$route['dashboard/script/testdate'] = 'script/dateHourTest';
$route['dashboard/script/addminicourses'] = 'script/addMinicourse';
$route['dashboard/script/uploadpaperview'] = 'script/uploadPaperView';
$route['dashboard/script/uploadpaper'] = 'script/uploadPaper';
$route['dashboard/script/report-teaching-cases'] = 'script/reportTeachingCases';
//$route['dashboard/script/asposterpending'] = 'script/asPosterPending';
$route['dashboard/script/rabstract'] = 'script/rabstract';
$route['dashboard/script/listErrors'] = 'script/listErrors';

/* Reports */
$route['dashboard/script/general-report'] = 'script/generalReport';

$route['validarcertificado'] = 'certificate/validateView';
$route['dashboard/certificate/validate'] = 'certificate/validate';

$route['dashboard/subevent/manage'] = 'subevent/manageView';
$route['dashboard/subevent/create'] = 'subevent/create';
$route['dashboard/subevent/remove'] = 'subevent/remove';
$route['dashboard/subevent/retrieveaddactivity'] = 'subevent/retrieveAddActivityView';
$route['dashboard/subevent/retrieveactivitiesresults'] = 'subevent/retrieveActivitiesResults';
$route['dashboard/subevent/addcustomactivity'] = 'subevent/addCustomActivity';
$route['dashboard/subevent/retrieveaddactivityform'] = 'subevent/retrieveAddActivityForm';
$route['dashboard/subevent/doexeaddactivity'] = 'subevent/doExeAddActivity';
$route['dashboard/subevent/retrieveedit'] = 'subevent/retrieveEditView';
$route['dashboard/subevent/removesubeventactivity'] = 'subevent/removeSubeventActivity';
$route['dashboard/subevent/retrieveupdateactivity'] = 'subevent/retrieveUpdateActivity';
$route['dashboard/subevent/doupdateactivity'] = 'subevent/doUpdateActivity';
$route['subevent/general/(:num)'] = 'welcome/generalView/$1';

$route['register'] = 'welcome/registerView';
$route['contact'] = 'welcome/contactView';
$route['submitMessage'] = 'welcome/submitMessage';
$route['schedule'] = 'welcome/scheduleView';
$route['conferences'] = 'welcome/conferencesView';
$route['minicourses'] = 'welcome/minicoursesView';
$route['roundtables'] = 'welcome/roundtablesView';
$route['workshops'] = 'welcome/workshopsView';
$route['shortfilms'] = 'welcome/shortFilmsView';
$route['conversationcircle'] = 'welcome/conversationCircleView';
$route['retrievedetailsgt'] = 'welcome/retrieveDetailGT';
$route['retrievestandard'] = 'welcome/retrieveStandard';
$route['debatescycle'] = 'welcome/debatescycle';
$route['anais'] = 'welcome/anaisView';
$route['activities'] = 'welcome/activitiesView';
$route['anaisworks'] = 'welcome/anaisWorksView';
$route['getCertificate'] = 'welcome/getCertificateView';

$route['dashboard/certificate/custom'] ='certificate/customView';
$route['dashboard/certificate/generate'] ='certificate/generate';
$route['dashboard/certificate/getcertificate'] ='certificate/getCertificateView';
$route['dashboard/certificate/createcustom'] ='certificate/createCustom';

$route['dashboard/certificate/paper'] ='certificate/paperView';
$route['dashboard/certificate/acceptpaper'] = 'certificate/acceptPaper';
$route['dashboard/certificate/rejectpaper'] = 'certificate/rejectPaper';
$route['dashboard/certificate/poster'] ='certificate/posterView';
$route['dashboard/certificate/acceptposter'] = 'certificate/acceptPoster';
$route['dashboard/certificate/rejectposter'] = 'certificate/rejectPoster';

$route['dashboard/certificate/teachingcase'] ='certificate/teachingcaseView';
$route['dashboard/certificate/acceptteachingcase'] = 'certificate/acceptTeachingCase';
$route['dashboard/certificate/rejectteachingcase'] = 'certificate/rejectTeachingCase';

$route['dashboard/certificate/minicourse'] ='certificate/minicourseView';
$route['dashboard/certificate/retrieveacceptminicourse'] ='certificate/retrieveAcceptMinicourse';
$route['dashboard/certificate/acceptminicourse'] = 'certificate/acceptMinicourse';
$route['dashboard/certificate/rejectminicourse'] = 'certificate/rejectMinicourse';
$route['dashboard/certificate/retrieveaddparticipant'] = 'certificate/retrieveAddParticipant';
$route['dashboard/certificate/revertminicourse'] = 'certificate/revertMinicourse';
$route['dashboard/certificate/retrieveparticipantslist'] = 'certificate/retrieveParticipantsList';

$route['dashboard/certificate/roundtable'] ='certificate/roundtableView';
$route['dashboard/certificate/retrieveacceptroundtable'] ='certificate/retrieveAcceptRoundtable';
$route['dashboard/certificate/acceptroundtable'] = 'certificate/acceptRoundtable';
$route['dashboard/certificate/rejectroundtable'] = 'certificate/rejectRoundtable';
$route['dashboard/certificate/revertroundtable'] = 'certificate/revertRoundtable';

$route['dashboard/certificate/conference'] ='certificate/conferenceView';
$route['dashboard/certificate/retrieveacceptconference'] ='certificate/retrieveAcceptConference';
$route['dashboard/certificate/acceptconference'] = 'certificate/acceptConference';
$route['dashboard/certificate/rejectconference'] = 'certificate/rejectConference';
$route['dashboard/certificate/revertconference'] = 'certificate/revertConference';

$route['dashboard/certificate/workshop'] ='certificate/workshopView';
$route['dashboard/certificate/retrieveacceptworkshop'] ='certificate/retrieveAcceptWorkshop';
$route['dashboard/certificate/acceptworkshop'] = 'certificate/acceptWorkshop';
$route['dashboard/certificate/rejectworkshop'] = 'certificate/rejectWorkshop';
$route['dashboard/certificate/revertworkshop'] = 'certificate/revertWorkshop';

$route['dashboard/certificate/retrieveparticipantslist'] = 'certificate/retrieveParticipantsList';
$route['dashboard/certificate/addparticipant'] = 'certificate/addParticipant';
$route['dashboard/certificate/retrieveaddparticipant'] = 'certificate/retrieveAddParticipant';

$route['dashboard/message'] ='message/index';
$route['dashboard/message/send'] ='message/sendView';
$route['dashboard/message/dosend'] ='message/doSend';
$route['dashboard/message/(:any)'] = 'message/index/$1';
$route['dashboard/message/retrievedetails'] = 'message/retrieveDetailsView';
$route['dashboard/message/reply'] = 'message/reply';

$route['dashboard/configuration'] ='configuration/index';
$route['dashboard/configuration/retrieveedit'] ='configuration/retrieveEdit';
$route['dashboard/configuration/update'] ='configuration/update';

$route['dashboard'] ='user';
$route['signin'] = 'user/loginView';
$route['login'] = 'user/login';
$route['resetpassword'] = 'user/resetPasswordView';
$route['resetp'] = 'user/resetPassword';

$route['dashboard/myInformation'] = 'user/myInformation';
$route['dashboard/updateUser'] = 'user/updateUser';
$route['createUser'] = 'user/createUser';

$route['logout'] = 'user/doLogout';
$route['dashboard/user/payment'] = 'user/paymentView';
$route['dashboard/user/paymentupload'] = 'user/paymentUpload';
$route['dashboard/user/submitpayment'] = 'user/submitPayment';
$route['dashboard/user/report'] = 'user/report';
$route['dashboard/user/createreport'] = 'user/createReport';
$route['dashboard/user/manage'] = 'user/manage';
$route['dashboard/user/manage/(:any)'] = 'user/manage/$1';
$route['dashboard/user/manage/retrievedetails'] = 'user/manageRetrieveDetails';
$route['dashboard/user/manage/retrieveevaluatepayment'] = 'user/manageRetrieveEvaluatePayment';
$route['dashboard/user/manage/acceptpayment'] = 'user/acceptPayment';
$route['dashboard/user/manage/rejectpayment'] = 'user/rejectPayment';
$route['dashboard/user/manage/freepayment'] = 'user/freePayment';
$route['dashboard/user/manage/retrieveLinkSearchByName'] = 'user/retrieveLinkSearchByName';
$route['dashboard/user/reportinscription/(:any)'] = 'user/reportInscriptionView';
$route['dashboard/user/reportinscription'] = 'user/reportInscriptionView';
$route['dashboard/user/reportinscription/retrieveLinkSearchByName'] = 'user/retrieveLinkSearchByNameReport';
$route['dashboard/user/createreportinscription/(:any)'] = 'user/createReportInscription';
$route['dashboard/user/myactivities'] = 'user/myActivitiesView';

$route['dashboard/issue/create'] = 'issue/createView';
$route['dashboard/issue/open'] = 'issue/create';
$route['dashboard/issue/uploadimage'] = 'issue/uploadImage';
$route['dashboard/issue/retrievedetails'] = 'issue/retrieveDetailsView';
$route['dashboard/issue/manage'] = 'issue/manageView';
$route['dashboard/issue/manage/(:any)'] = 'issue/manageView/$1';
$route['dashboard/issue/reply'] = 'issue/reply';
$route['dashboard/issue/close'] = 'issue/close';
$route['dashboard/issue/userretrievedetails'] = 'issue/userRetrieveDetailsView';
$route['dashboard/issue/userreply'] = 'issue/userReply';

$route['dashboard/paper/submit'] = 'paper/submitView';
$route['dashboard/paper/create'] = 'paper/create';
$route['dashboard/paper/uploadpaper'] = 'paper/uploadPaper';
$route['dashboard/paper/evaluate'] = 'paper/evaluateView';
$route['dashboard/paper/retrievepaperdetails'] = 'paper/retrievePaperDetailsView';
$route['dashboard/paper/reject'] = 'paper/reject';
$route['dashboard/paper/accept'] = 'paper/accept';
$route['dashboard/paper/get-paper'] = 'paper/getPaper';
$route['dashboard/paper/cancelsubmission'] = 'paper/cancelSubmission';
//$route['dashboard/paper/acceptasposter'] = 'paper/acceptAsPoster';
//$route['dashboard/paper/useracceptasposter'] = 'paper/userAcceptAsPoster';
//$route['dashboard/paper/userrejectasposter'] = 'paper/userRejectAsPoster';
$route['paper/get-source'] = 'paper/getSource';
$route['dashboard/advanced-control/paper'] = 'paper/advancedControl';

$route['dashboard/poster/submit'] = 'poster/submitView';
$route['dashboard/poster/create'] = 'poster/create';
$route['dashboard/poster/evaluate'] = 'poster/evaluateView';
$route['dashboard/poster/retrieveposterdetails'] = 'poster/retrievePosterDetailsView';
$route['dashboard/poster/reject'] = 'poster/reject';
$route['dashboard/poster/accept'] = 'poster/accept';
$route['dashboard/poster/cancelsubmission'] = 'poster/cancelSubmission';
$route['dashboard/poster/uploadposter'] = 'poster/uploadPoster';
$route['dashboard/poster/get-poster'] = 'poster/getPoster';
$route['dashboard/poster/upload-later-do'] = 'poster/uploadLaterDo';
$route['poster/get-source'] = 'poster/getSource';

$route['dashboard/minicourse'] = 'minicourse/index';
$route['dashboard/minicourse/submit'] = 'minicourse/submitView';
$route['dashboard/minicourse/uploadprogram'] = 'minicourse/uploadProgram';
$route['dashboard/minicourse/create'] = 'minicourse/create';
$route['dashboard/minicourse/createdayshift'] = 'minicourse/createDayShift';
$route['dashboard/minicourse/retrieveconsolidation'] = 'minicourse/retrieveConsolidationView';
$route['dashboard/minicourse/consolidate'] = 'minicourse/consolidate';
$route['dashboard/minicourse/deallocate'] = 'minicourse/deallocate';
$route['dashboard/minicourse/deletedayshift'] = 'minicourse/deleteDayShift';
$route['dashboard/minicourse/retrievedetails'] = 'minicourse/retrieveDetailsView';
$route['dashboard/minicourse/manage'] = 'minicourse/manageView';
$route['dashboard/minicourse/manage/(:any)'] = 'minicourse/manageView/$1';
$route['dashboard/minicourse/enroll'] = 'minicourse/enrollView';
$route['dashboard/minicourse/enrolla'] = 'minicourse/enrolla';
$route['dashboard/minicourse/unroll'] = 'minicourse/unroll';
$route['dashboard/minicourse/retrieveenrolldetails'] = 'minicourse/retrieveEnrollDetailsView';
$route['dashboard/minicourse/cancelsubmission'] = 'minicourse/cancelSubmission';
$route['dashboard/minicourse/report'] = 'minicourse/reportView';
$route['dashboard/minicourse/createreport'] = 'minicourse/createReport';
$route['dashboard/minicourse/retrieveconfirmoperation'] = 'minicourse/retrieveConfirmOperation';
$route['dashboard/minicourse/retrieveeditinfo'] = 'minicourse/retrieveEditInfo';
$route['dashboard/minicourse/updateconsolidated'] = 'minicourse/updateConsolidated';
$route['dashboard/minicourse/my-minicourses'] = 'minicourse/myMinicoursesView';

$route['dashboard/workshop/manage'] = 'workshop/manageView';
$route['dashboard/workshop/manage/(:any)'] = 'workshop/manageView/$1';
$route['dashboard/workshop/enroll'] = 'workshop/enrollView';
$route['dashboard/workshop/retrieveenrolldetails'] = 'workshop/retrieveEnrollDetails';
$route['dashboard/workshop/enrolla'] = 'workshop/enrolla';
$route['dashboard/workshop/unroll'] = 'workshop/unroll';
$route['dashboard/workshop/report'] = 'workshop/reportView';
$route['dashboard/workshop/createreport'] = 'workshop/createReport';
$route['dashboard/workshop/submit'] = 'workshop/submitView';
$route['dashboard/workshop/uploadprogram'] = 'workshop/uploadProgram';
$route['dashboard/workshop/create'] = 'workshop/create';
$route['dashboard/workshop/cancelsubmission'] = 'workshop/cancelSubmission';
$route['dashboard/workshop/createdayshift'] = 'workshop/createDayShift';
$route['dashboard/workshop/deletedayshift'] = 'workshop/deleteDayShift';
$route['dashboard/workshop/retrieveconsolidation'] = 'workshop/retrieveConsolidationView';
$route['dashboard/workshop/consolidate'] = 'workshop/consolidate';
$route['dashboard/workshop/retrieveconfirmoperation'] = 'workshop/retrieveConfirmOperation';
$route['dashboard/workshop/deallocate'] = 'workshop/deallocate';
$route['dashboard/workshop/retrieveworkshopdetails'] = 'workshop/retrieveWorkshopDetails';
$route['dashboard/workshop/retrieveeditinfo'] = 'workshop/retrieveEditInfo';
$route['dashboard/workshop/updateconsolidated'] = 'workshop/updateConsolidated';

$route['dashboard/conference'] = 'conference/manageView';
$route['dashboard/conference/createdayshift'] = 'conference/createDayShift';
$route['dashboard/conference/deletedayshift'] = 'conference/deleteDayShift';
$route['dashboard/conference/create'] = 'conference/create';
$route['dashboard/conference/retrievedetails'] = 'conference/retrieveDetailsView';
$route['dashboard/conference/delete'] = 'conference/delete';
$route['dashboard/conference/enroll'] = 'conference/enrollView';
$route['dashboard/conference/enrolla'] = 'conference/enrolla';
$route['dashboard/conference/unroll'] = 'conference/unroll';
$route['dashboard/conference/retrieveenrolldetails'] = 'conference/retrieveEnrollDetailsView';
$route['dashboard/conference/retrieveedit'] = 'conference/retrieveEditView';
$route['dashboard/conference/update'] = 'conference/update';
$route['dashboard/conference/report'] = 'conference/reportView';
$route['dashboard/conference/createreport'] = 'conference/createReport';

$route['dashboard/roundtable/submit'] = 'roundtable/submitView';
$route['dashboard/roundtable/create'] = 'roundtable/create';
$route['dashboard/roundtable/createdayshift'] = 'roundtable/createDayShift';
$route['dashboard/roundtable/retrieveconsolidation'] = 'roundtable/retrieveConsolidationView';
$route['dashboard/roundtable/consolidate'] = 'roundtable/consolidate';
$route['dashboard/roundtable/deallocate'] = 'roundtable/deallocate';
$route['dashboard/roundtable/deletedayshift'] = 'roundtable/deleteDayShift';
$route['dashboard/roundtable/retrievedetails'] = 'roundtable/retrieveDetailsView';
$route['dashboard/roundtable/manage'] = 'roundtable/manageView';
$route['dashboard/roundtable/manage/(:any)'] = 'roundtable/manageView/$1';
$route['dashboard/roundtable/enroll'] = 'roundtable/enrollView';
$route['dashboard/roundtable/enrolla'] = 'roundtable/enrolla';
$route['dashboard/roundtable/unroll'] = 'roundtable/unroll';
$route['dashboard/roundtable/retrieveenrolldetails'] = 'roundtable/retrieveEnrollDetailsView';
$route['dashboard/roundtable/cancelsubmission'] = 'roundtable/cancelSubmission';
$route['dashboard/roundtable/report'] = 'roundtable/reportView';
$route['dashboard/roundtable/createreport'] = 'roundtable/createReport';
$route['dashboard/roundtable/retrieveconfirmoperation'] = 'roundtable/retrieveConfirmOperation';
$route['dashboard/roundtable/retrieveeditinfo'] = 'roundtable/retrieveEditInfo';
$route['dashboard/roundtable/updateconsolidated'] = 'roundtable/updateConsolidated';


$route['dashboard/teachingcases/submit'] = 'teachingcases/submitView';
$route['dashboard/teachingcases/create'] = 'teachingcases/create';
$route['dashboard/teachingcases/uploadteachingcase'] = 'teachingcases/uploadTeachingCase';
$route['dashboard/teachingcases/cancelsubmission'] = 'teachingcases/cancelSubmission';
$route['dashboard/teachingcases/evaluate'] = 'teachingcases/evaluateView';
$route['dashboard/teachingcases/retrieveteachingcasedetails'] = 'teachingcases/retrieveView';
$route['dashboard/teachingcases/reject'] = 'teachingcases/reject';
$route['dashboard/teachingcases/accept'] = 'teachingcases/accept';
$route['dashboard/teachingcases/get-teaching-case'] = 'teachingcases/getTeachingCase';

$route['dashboard/area'] = 'area/manage';
$route['dashboard/area/retrievedetails'] = 'area/retrieveDetailsView';
$route['dashboard/area/create'] = 'area/create';
$route['dashboard/area/update'] = 'area/update';
$route['dashboard/area/delete'] = 'area/delete';

$route['dashboard/thematicgroup'] = 'thematicgroup/index';
$route['dashboard/thematicgroup/(:any)'] = 'thematicgroup/index/$1';
$route['dashboard/thematicgroup/retrievedetails'] = 'thematicgroup/retrieveDetailsView';
$route['dashboard/thematicgroup/create'] = 'thematicgroup/create';
$route['dashboard/thematicgroup/update'] = 'thematicgroup/update';
$route['dashboard/thematicgroup/delete'] = 'thematicgroup/delete';

$route['dashboard/coordinator'] = 'coordinator/index';;
$route['dashboard/coordinator/(:any)'] = 'coordinator/index/$1';
$route['dashboard/coordinator/retrievedetails'] = 'coordinator/retrieveDetailsView';
$route['dashboard/coordinator/create'] = 'coordinator/create';
$route['dashboard/coordinator/update'] = 'coordinator/update';
$route['dashboard/coordinator/delete'] = 'coordinator/delete';
$route['dashboard/coordinator/user2coordinator'] = 'coordinator/user2coordinator';

$route['dashboard/administrator'] = 'administrator/index';
$route['dashboard/administrator/retrievedetails'] = 'administrator/retrieveDetailsView';
$route['dashboard/administrator/create'] = 'administrator/create';
$route['dashboard/administrator/update'] = 'administrator/update';
$route['dashboard/administrator/delete'] = 'administrator/delete';

$route['news'] = 'news/mainView';
$route['dashboard/news'] = 'news/index';
$route['dashboard/news/retrievedetails'] = 'news/retrieveDetailsView';
$route['dashboard/news/create'] = 'news/create';
$route['dashboard/news/update'] = 'news/update';
$route['dashboard/news/delete'] = 'news/delete';

$route['dashboard/schedule/paper'] = 'schedule/paperManageView';
$route['dashboard/schedule/paper/newrecord'] = 'schedule/paperNewRecord';
$route['dashboard/schedule/poster'] = 'schedule/posterManageView';
$route['dashboard/schedule/poster/newrecord'] = 'schedule/posterNewRecord';
$route['dashboard/schedule/teachingcase'] = 'schedule/teachingcaseView';
$route['dashboard/schedule/teachingcase/newrecord'] = 'schedule/teachingcaseNewRecord';
$route['dashboard/schedule/roundtable'] = 'schedule/roundtableManageView';
$route['dashboard/schedule/roundtable/newrecord'] = 'schedule/roundtableNewRecord';
$route['dashboard/schedule/workshop'] = 'schedule/workshopView';
$route['dashboard/schedule/workshop/newrecord'] = 'schedule/workshopNewRecord';
$route['dashboard/schedule/minicourse'] = 'schedule/minicourseManageView';
$route['dashboard/schedule/minicourse/newrecord'] = 'schedule/minicourseNewRecord';
$route['dashboard/schedule/othersactivities'] = 'schedule/othersActivitiesManageView';
$route['dashboard/schedule/othersactivities/newrecord'] = 'schedule/othersActivitiesNewRecord';
$route['dashboard/schedule/conference'] = 'schedule/conferenceManageView';
$route['dashboard/schedule/conference/newrecord'] = 'schedule/conferenceNewRecord';
$route['dashboard/schedule/generate'] = 'schedule/generateView';
$route['dashboard/schedule/removerecord'] = 'schedule/removeRecord';
$route['dashboard/schedule/retrieveworksbytg'] = 'schedule/retrieveWorksByTg';

/* End of file routes.php */
/* Location: ./application/config/routes.php */
