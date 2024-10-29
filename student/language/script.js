function loadLanguage(language) {
  fetch(`language/${language}.json`)
    .then((response) => {
      if (!response.ok) {
        throw new Error("Network response was not ok");
      }
      return response.json();
    })
    .then((translations) => {
      if (document.getElementById("reg_button") != null) {
        document.getElementById("reg_button").innerText =
          translations.reg_button;
      }
      if (document.getElementById("sub_title") != null) {
        document.getElementById("sub_title").innerText = translations.sub_title;
        document.getElementById("academicYear").innerText =
          translations.academicYear;
        document.getElementById("datecreated").innerText =
          translations.datecreated;
        document.getElementById("closingdate").innerText =
          translations.closingdate;
        document.getElementById("exam_regtr").innerText = translations.exam_reg;
      }
      if(document.getElementById("noexam") != null){
        document.getElementById("noexam").innerHTML = translations.noexam;
      }
      document.getElementById("fullnametr").innerHTML = translations.fullname;
      document.getElementById("districttr").innerHTML = translations.district;
      document.getElementById("mobileNotr").innerText = translations.mobileNo;
      document.getElementById("landlineNotr").innerText =
        translations.landlineNo;
      document.getElementById("jaffna_addresstr").innerText =
        translations.jaffna_address;
      document.querySelectorAll("#typetr").forEach((element) => {
        element.innerText = translations.type;
      });
      document.querySelectorAll("#leveltr").forEach((element) => {
        element.innerText = translations.level;
      });
      document.querySelectorAll("#semestertr").forEach((element) => {
        element.innerText = translations.semester;
      });

      //document.getElementById('leveltr').innerText = translations.level;
      //document.getElementById('semestertr').innerText = translations.semester;
      //document.getElementById('typetr').innerText = translations.type;
      if (document.getElementById("datetr") != null) {
        document.getElementById("subject_combinationtr").innerText =
          translations.subject_combination;
        document.getElementById("datetr").innerText = translations.date;
        document.getElementById("actiontr").innerText = translations.action_;
      }
      if (document.getElementById("title-exhist") != null) {
        document.getElementById("title-exhist").innerText =
          translations.title_exhist;
        document.getElementById("update").innerText = translations.update;
      }
      if (document.getElementById("nameWithInitialtr") != null) {
        document.getElementById("nameWithInitialtr").innerText =
          translations.nameWithInitial;
        document.getElementById("home_addresstr").innerText =
          translations.home_address;
        document.getElementById("regNotr").innerText = translations.regNo;
        document.getElementById("titletr").innerText = translations.title;
      }
      if (document.getElementById("text01") != null) {
        document.getElementById("text01").innerHTML =
          translations.exam_registation.text01;
        document.getElementById("text02").innerText =
          translations.exam_registation.text02;
        document.getElementById("text03").innerText =
          translations.exam_registation.text03;
        document.getElementById("text04").innerText =
          translations.exam_registation.text04;
        document.getElementById("text05").innerText =
          translations.exam_registation.text05;
        document.getElementById("text06").innerHTML =
          translations.exam_registation.text06;
        document.getElementById("text07").innerHTML =
          translations.exam_registation.text07;
        document.getElementById("text08").innerHTML =
          translations.exam_registation.text08;
        document.getElementById("text09").innerHTML =
          translations.exam_registation.text09;
        document.getElementById("text10").innerHTML =
          translations.exam_registation.text10;
        document.getElementById("text11").innerHTML =
          translations.exam_registation.text11;
        document.getElementById("text12").innerHTML =
          translations.exam_registation.text12;
        document.getElementById("text13").innerHTML =
          translations.exam_registation.text13;
        document.getElementById("text14").innerHTML =
          translations.exam_registation.text14;
        document.getElementById("text15").innerHTML =
          translations.exam_registation.text15;
        document.getElementById("text16").innerHTML =
          translations.exam_registation.text16;
        document.getElementById("text17").innerHTML =
          translations.exam_registation.text17;
        document.getElementById("perdetaitr").innerText =
          translations.perdetaitr;
      }
      if (document.getElementById("selunitetr") != null) {
        document.getElementById("selunitetr").innerHTML =
          translations.selunitetr;
        document.getElementById("selunitetexttr").innerHTML =
          translations.selunitetexttr;
      }
      if (document.getElementById("psctext01") != null) {
        document.getElementById("psctext01").innerHTML =
          translations.Payment_Slip_copies.psctext01;
        document.getElementById("psctext02").innerHTML =
          translations.Payment_Slip_copies.psctext02;
        document.getElementById("psctext03").innerHTML =
          translations.Payment_Slip_copies.psctext03;
        document.getElementById("psctext04").innerHTML =
          translations.Payment_Slip_copies.psctext04;
        document.getElementById("psctext05").innerHTML =
          translations.Payment_Slip_copies.psctext05;
        document.getElementById("psctext06").innerHTML =
          translations.Payment_Slip_copies.psctext06;
        document.getElementById("psctext07").innerHTML =
          translations.Payment_Slip_copies.psctext07;
        document.getElementById("psctext08").innerHTML =
          translations.Payment_Slip_copies.psctext08;
        document.getElementById("psctext09").innerHTML =
          translations.Payment_Slip_copies.psctext09;
        document.getElementById("psctext10").innerHTML =
          translations.Payment_Slip_copies.psctext10;
        document.getElementById("psctext11").innerHTML =
          translations.Payment_Slip_copies.psctext11;
      }
    })
    .catch((error) => console.error("Error loading language file:", error));
}

document.addEventListener("DOMContentLoaded", () => {
  const languageSwitcher = document.getElementById("languageSwitcher");
  const lang = localStorage.getItem("language") || "en";
  languageSwitcher.value = lang;
  loadLanguage(lang);

  languageSwitcher.addEventListener("change", function () {
    const selectedLanguage = this.value;
    //console.log(selectedLanguage);
    localStorage.setItem("language", selectedLanguage);
    loadLanguage(selectedLanguage);
  });
});
