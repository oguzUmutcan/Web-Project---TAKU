const policyTitle = document.querySelector(".policy-title");
const policyContent = document.querySelector(".policy-content");

policyTitle.addEventListener("click", () => {
  // aç/kapat
  if (policyContent.style.display === "block") {
    policyContent.style.display = "none";
  } else {
    policyContent.style.display = "block";
  }
});
