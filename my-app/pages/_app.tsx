import '../styles/globals.css'
import "bootstrap/dist/css/bootstrap.css"
import type { AppProps } from 'next/app'
import Head from 'next/head'
import Script from 'next/script'
import { SWRConfig } from "swr";
import axios from 'axios';

function MyApp({ Component, pageProps }: AppProps) {
  const fetcher = (url:string) => axios.get(url).then((res)=>res.data)
  return (
    <>
      <Script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js">
        {/* <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="annymous"></script> */}
      </Script>
      <SWRConfig value={{fetcher:fetcher}}>
        <Component {...pageProps} />
      </SWRConfig>
    </>
  )
}

export default MyApp
